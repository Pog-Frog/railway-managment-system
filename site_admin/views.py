from django.shortcuts import render, redirect, HttpResponseRedirect
from django.http import JsonResponse, HttpResponse
from django.contrib.auth.hashers import make_password, check_password
from main.models import Admin
from django.contrib.auth import login as auth_login
from django.contrib.auth import logout as auth_logout
from django.contrib.auth import views as auth_views

# Create your views here.


def index_login(request):
    return render(request, 'admin/login.html', {})


def login(request):
    if request.method == "POST":
        email = request.POST["email"]
        password = request.POST["password"]
        try:
            users = Admin.objects.all()
            for user in users:
                if user.email == email:
                    if check_password(password, user.password):
                        auth_login(request, user)
                        return redirect(request, '/dashboard/')
                    else:
                        return render(request, 'admin/login.html',
                                      context={"flag": True, "error": "password of email is not correct"})
            return render(request, 'admin/login.html',
                          context={"flag": True, "error": "password of email is not correct"})
        except Exception as error:
            print(error)


def logout(request):
    logout(request)
    redirect('../templates/admin/login.html')


def index(request):
    return render(request, '../templates/admin/base.html')

def test(request):
    boards = Admin.objects.get(id=1)
    return render(request, "admin/test.html", {'boards': boards})