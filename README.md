# Laravel Vue Map Users.
 
## About project 
The project shows the all users in the map based on it's latitude and longitude along with user filters.

---
## Preview
### All Users shown in the MAP
![All Users shown in the MAP](https://i.imgur.com/wsrlvCi.png)


### Gender wise Male User showing in the map
![Gender Filter wise User showing in the map](https://i.imgur.com/nsuQPrk.png)

### Gender wise Female User showing in the map
![Gender Filter wise User showing in the map](https://i.imgur.com/fJm0r6G.png)


### Users by First name
![Users by First name](https://i.imgur.com/fVzKWqZ.png)

### Users by Last name
![Users by Last name](https://i.imgur.com/pgTUN5e.png)

### Closest users around 2000 km.
![Closest users around 2000 km](https://i.imgur.com/ZJHzQZv.png)


## Installation process

### Clone the repo.
```
git clone https://github.com/vikas-ukani/Locate-Users.git
cd Locate-Users
```

### Create .env file
```
cp .env.example .env
```

### Install pacakges.
- Install php laravel pacakges
```
composer install && npm install 
```


- Configure local database connection.
###  Generate key if it's required, Run the command 
```
php artisan key:generate
```

### Run the PHP server
```
php artisan serve
```

### Run Vue server
```
npm run dev
```

### Visit the site.
```
http://localhost:8000/
```