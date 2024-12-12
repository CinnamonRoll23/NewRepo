from django.urls import path, include
from rest_framework.routers import DefaultRouter
from .views import OrderViewSet, cart_items, create_user, get_user, checkout

router = DefaultRouter()
router.register(r'orders', OrderViewSet, basename='order')

urlpatterns = [
    path('api/', include(router.urls)),
    path('api/cart-items/', cart_items, name='cart-items'),
    path('api/users/', create_user, name='create-user'),
    path('api/users/<uuid:pk>/', get_user, name='get-user'),
    path('api/checkout/<uuid:pk>/', checkout, name='checkout'),
]