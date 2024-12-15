from rest_framework.views import APIView
from rest_framework import viewsets, status
from rest_framework.permissions import IsAuthenticated
from rest_framework.decorators import action
from rest_framework.response import Response
from .models import User, Order, CartItem
from .serializers import UserSerializer, OrderSerializer, CartItemSerializer

class UserViewSet(viewsets.ModelViewSet):
    """
    Handles CRUD operations for User.
    """
    queryset = User.objects.all()
    serializer_class = UserSerializer

class OrderViewSet(viewsets.ModelViewSet):
    """
    Handles CRUD operations for Orders.
    """
    queryset = Order.objects.all()
    serializer_class = OrderSerializer

    @action(detail=True, methods=['post'])
    def checkout(self, request, pk=None):
        """
        Custom action for order checkout.
        """
        try:
            order = self.get_object()
            if order.status != 'Pending':
                return Response({"error": "Only pending orders can be checked out."}, status=status.HTTP_400_BAD_REQUEST)
            
            cart_items = order.cart_items.all()
            if not cart_items.exists():
                return Response({"error": "No cart items found for this order."}, status=status.HTTP_400_BAD_REQUEST)


            order.status = 'Processed'
            order.save()
            return Response({"message": "Order successfully checked out."}, status=status.HTTP_200_OK)
        except Order.DoesNotExist:
            return Response({"error": "Order not found."}, status=status.HTTP_404_NOT_FOUND)

class CartItemViewSet(viewsets.ModelViewSet):
    """
    Handles CRUD operations for Cart Items.
    """
    queryset = CartItem.objects.all()
    serializer_class = CartItemSerializer

    def get_queryset(self):
        """
        Optionally filter by order status = Pending.
        """
        order_id = self.request.query_params.get('order_id', None)
        if order_id:
            return CartItem.objects.filter(order__id=order_id, order__status='Pending')
        return super().get_queryset()

class OrderListView(APIView):
    """
    get:
    Retrieve a list of all orders.

    post:
    Create a new order.
    """
    permission_classes = [IsAuthenticated]

    def get(self, request):
        # Retrieve all orders for the authenticated user
        orders = Order.objects.filter(user=request.user)
        serializer = OrderSerializer(orders, many=True)
        return Response(serializer.data, status=status.HTTP_200_OK)

    def post(self, request):
        # Create a new order for the authenticated user
        data = request.data
        data['user'] = request.user.id  # Attach the authenticated user to the order
        serializer = OrderSerializer(data=data)

        if serializer.is_valid():
            serializer.save()
            return Response(serializer.data, status=status.HTTP_201_CREATED)

        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)
