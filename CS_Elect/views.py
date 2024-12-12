from rest_framework import viewsets
from rest_framework.response import Response
from rest_framework import status
from rest_framework.decorators import api_view
from django.shortcuts import get_object_or_404
from drf_yasg.utils import swagger_auto_schema
from .models import User, Order, CartItem
from .serializer import UserSerializer, OrderSerializer, CartItemSerializer

class OrderViewSet(viewsets.ViewSet):
    def list(self, request):
        orders = Order.objects.all()
        serializer = OrderSerializer(orders, many=True)
        return Response(serializer.data)

    def retrieve(self, request, pk=None):
        order = get_object_or_404(Order, pk=pk)
        serializer = OrderSerializer(order)
        return Response(serializer.data)

    def create(self, request):
        serializer = OrderSerializer(data=request.data)
        if serializer.is_valid():
            serializer.save()
            return Response(serializer.data, status=status.HTTP_201_CREATED)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

    def update(self, request, pk=None):
        order = get_object_or_404(Order, pk=pk)
        serializer = OrderSerializer(order, data=request.data)
        if serializer.is_valid():
            serializer.save()
            return Response(serializer.data)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

    def destroy(self, request, pk=None):
        order = get_object_or_404(Order, pk=pk)
        order.delete()
        return Response(status=status.HTTP_204_NO_CONTENT)


# Cart Items API View
@api_view(['GET', 'POST'])
def cart_items(request):
    if request.method == 'GET':
        items = CartItem.objects.filter(order__status='Pending')
        serializer = CartItemSerializer(items, many=True)
        return Response(serializer.data)
    elif request.method == 'POST':
        serializer = CartItemSerializer(data=request.data)
        if serializer.is_valid():
            serializer.save()
            return Response(serializer.data, status=status.HTTP_201_CREATED)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)


# Create User API View
@swagger_auto_schema(
    method='post',
    request_body=UserSerializer,
    responses={201: UserSerializer, 400: 'Bad Request'}
)
@api_view(['POST'])
def create_user(request):
    serializer = UserSerializer(data=request.data)
    if serializer.is_valid():
        serializer.save()
        return Response(serializer.data, status=status.HTTP_201_CREATED)
    return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)


# Get User API View
@swagger_auto_schema(
    method='get',
    responses={200: UserSerializer, 404: 'Not Found'}
)
@api_view(['GET'])
def get_user(request, pk):
    user = get_object_or_404(User, pk=pk)
    serializer = UserSerializer(user)
    return Response(serializer.data)


# Checkout API View
@api_view(['POST'])
def checkout(request, pk):
    order = get_object_or_404(Order, pk=pk, status='Pending')
    if not order.cart_items.exists():
        return Response({"error": "No cart items found."}, status=status.HTTP_400_BAD_REQUEST)
    order.status = 'Processed'
    order.save()
    return Response({"message": "Checkout successful."}, status=status.HTTP_200_OK)
