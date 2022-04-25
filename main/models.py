from django.contrib.auth.models import User
from django.db import models
from passlib.hash import pbkdf2_sha256


# Create your models here.

# noinspection DuplicatedCode
class Admin(models.Model):
    name = models.CharField(max_length=255, blank=True, default="null")
    username = models.CharField(max_length=255, blank=False, unique=True)
    password = models.TextField(blank=False)
    email = models.CharField(max_length=255, blank=False)

    def get_admin_by_email(x):
        try:
            return Admin.objects.get(email=x)
        except:
            return False

    def verify_pass(self, encrypted):
        return pbkdf2_sha256.verify(encrypted, self.password)


class Technician(models.Model):
    name = models.CharField(max_length=255, blank=True, default="null")
    user_name = models.CharField(max_length=255, blank=False, unique=True)
    password = models.TextField(blank=False)
    email = models.CharField(max_length=255, blank=False)

    def verify_pass(self, encrypted):
        return pbkdf2_sha256.verify(encrypted, self.password)


class Passenger(models.Model):
    name = models.CharField(max_length=255, blank=False)
    user_name = models.CharField(max_length=255, blank=False, unique=True)
    email = models.CharField(max_length=255, blank=False)
    password = models.TextField(blank=False)
    Male = 'M'
    Female = 'F'
    Genders = [
        (Male, 'Male'),
        (Female, 'Female')
    ]
    gender = models.CharField(max_length=6, choices=Genders, blank=False)
    phone = models.CharField(max_length=15)

    def verify_pass(self, encrypted):
        return pbkdf2_sha256.verify(encrypted, self.password)


class Employee(models.Model):
    name = models.CharField(max_length=255, blank=True, default="null")
    user_name = models.CharField(max_length=255, blank=False, unique=True)
    password = models.TextField(blank=False)
    email = models.CharField(max_length=255, blank=False)

    def verify_pass(self, encrypted):
        return pbkdf2_sha256.verify(encrypted, self.password)


class Stations(models.Model):
    name = models.CharField(max_length=255, blank=False, unique=True)
    city = models.CharField(max_length=255, blank=False)


class Train(models.Model):
    name = models.CharField(max_length=255, blank=False, unique=True)
    type = models.TextField(blank=False)  # type of the train (VIP, sleeper, etc .. )
    no_of_cars = models.CharField(max_length=255)


class Trip(models.Model):
    arrival_station = models.ForeignKey(Stations, related_name="arrival_station", on_delete=models.CASCADE)
    departure_station = models.ForeignKey(Stations, related_name="departure_station", on_delete=models.CASCADE)
    train = models.ForeignKey(Train, related_name="trips", on_delete=models.CASCADE)
    departure_time = models.CharField(max_length=255, blank=False)
    arrival_time = models.CharField(max_length=255, blank=False)


class Captain(models.Model):
    name = models.CharField(max_length=255, blank=True, default="null")
    user_name = models.CharField(max_length=255, blank=False, unique=True)
    password = models.TextField(blank=False)
    email = models.CharField(max_length=255, blank=False)
    train = models.ForeignKey(Train, related_name="drive", blank=True, on_delete=models.CASCADE)
    trip = models.ForeignKey(Trip, related_name="assign", blank=True, on_delete=models.CASCADE)

    def verify_pass(self, encrypted):
        return pbkdf2_sha256.verify(encrypted, self.password)


class Report(models.Model):
    details = models.TextField(blank=False)
    send_by = models.ForeignKey(Captain, related_name="write", blank=False, on_delete=models.CASCADE)
    date_sent = models.DateTimeField(auto_now_add=True)


class Ticket(models.Model):
    trip = models.ForeignKey(Trip, related_name="has_trip", on_delete=models.CASCADE)
    passenger = models.ForeignKey(Passenger, related_name="book", on_delete=models.CASCADE)
    booked_by = models.ForeignKey(Employee, related_name="book", on_delete=models.CASCADE)
    date = models.DateTimeField(auto_now_add=True)
    payment_status = models.BooleanField(blank=False, default=False)
