FROM php:8.2-cli

# Thư mục làm việc trong container
WORKDIR /app

# Copy toàn bộ code vào container
COPY . .

# Mở cổng web
EXPOSE 10000

# Chạy server PHP
CMD ["php", "-S", "0.0.0.0:10000"]
