FROM node:12-alpine

# Create app dir
RUN mkdir -p /app
WORKDIR /app

# Install dependencies
RUN apk update && apk add git python make g++