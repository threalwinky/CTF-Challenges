FROM node:20-bullseye-slim

WORKDIR /app

RUN apt update && apt install -y wget gnupg curl \
  && wget -q -O - https://dl-ssl.google.com/linux/linux_signing_key.pub | apt-key add - \
  && echo "deb http://dl.google.com/linux/chrome/deb/ stable main" >> /etc/apt/sources.list.d/google.list \
  && apt update && apt install -y --no-install-recommends google-chrome-stable \
  && rm -rf /var/lib/apt/lists/

COPY . .

RUN npm install

CMD ["npm", "start"]