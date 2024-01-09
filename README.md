# Dinacom 2024 Backend

This is Backend Project for Dinacom 2024 Competition

### How to run

Make sure you have installed Docker on your computer

```bash
$ ./build.sh
```

For first deployment, run database seeder using command

```bash
$ docker exec dinacom24-app-container php artisan migrate:fresh --seed
```
