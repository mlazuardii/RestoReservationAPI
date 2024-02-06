## Information

This project submitted to fulfill job test requirement.

- [Muhammad Lazuardi Imani](https://www.linkedin.com/in/muhammadlazuardiimani/).
- lazuardi.crs@gmail.com.

## Instalation Guide

- Clone/Download Repository
- Set up your .env file
- Write command in Terminal `composer update`
- After finished, write this comand in Terminal `php artisan serve`
- Open and test the code in **Postman**

## System Scope

- There is no check-in time and cancellation feature. However, users have the option to delete existing reservations and create new ones according to their preferences.
- In creating a reservation, the user only needs to input the check-in date, and the system will automatically calculate the check-out date based on the selected menu category.
- There is no check-in time and cancellation feature. However, users have the option to delete existing reservations and create new ones according to their preferences.

## Steps

1. Registration
**API URL**
POST
`127.0.0.1:8000/api/register`

**Data Required**
- name
- email
- password

2. Login and Get Token
**API URL**
POST
`127.0.0.1:8000/api/login`

**Data Required**
- email
- password

**Data Return**
- access_token
- token_type

3. Create a Reservation
**API URL**
POST
`127.0.0.1:8000/api/create`

**Data Required**
- name
- table_no
- menu_category
- datetime_start

4. Look Up the Reservation Detail
**API URL**
GET
`127.0.0.1:8000/api/detail/{id}`

**Data Required**
- id

5. Look Up All Data
**API URL**
GET
`127.0.0.1:8000/api/list`

6. Delete Data
**API URL**
DELETE
`127.0.0.1:8000/api/delete/{id}`

**Data Required**
- id

7. Logout
**API URL**
POST
`127.0.0.1:8000/api/logout`

### Unit Testing

[OK] Same Table, Same Time in Between
[OK] Different Table, Same Time in Between
[OK] Same Table, Different Time in Between
[OK] Wrong data validation
[OK] True data validation


Thank you! Have a nice day!
