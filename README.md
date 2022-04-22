## How To Get Tokens

After clone please run `php artisan db:seed` command
to add admin user to user table and after that
you can register new users by calling `/api/register` api address.
Note: For giving a user a Delivery Man access put `delivery man`
in role input and `partner` to giving the user Partners privilege.

To get tokens you need to login by calling `/api/login` api address. After that you can 
call address by setting given to as type of Bearer Token in authentication header.

Also a collection have added to the project for importing to Postman.
