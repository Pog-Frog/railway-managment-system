from django.urls import path, include
from . import views
from django.contrib.auth import views as auth_views

urlpatterns = [
    path('employee/admin/test', views.test, name="test"),
    path('employees/admin/', views.index_login, name="admin_index_login"),
    path('employees/admin/login', views.login, name="admin_login"),
    path('employees/admin/logout', views.logout, name="admin_logout"),
    path('employees/admin/dashboard', views.index, name="index"),





    path('employees/admin/rest_password', auth_views.PasswordResetView.as_view(), name="password_reset"),
    path('employees/admin/password_reset/done', auth_views.PasswordResetDoneView.as_view(), name="password_reset_done"),
    path('employees/admin/reset/<udi64>/<token>', auth_views.PasswordResetConfirmView.as_view(),
         name="password_reset_confirm"),
    path('employees/admin/reset/done', auth_views.PasswordResetCompleteView.as_view(), name="password_reset_complete")
]
