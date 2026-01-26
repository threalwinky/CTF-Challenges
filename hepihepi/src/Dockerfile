FROM node:20

WORKDIR /app

RUN apt-get update && apt-get install -y gcc && rm -rf /var/lib/apt/lists/*

COPY package.json ./
RUN npm install

COPY readflag.c /readflag.c
RUN gcc /readflag.c -o /readflag && \
    chmod 4755 /readflag && \
    rm /readflag.c

COPY flag.txt /flag.txt
RUN chmod 600 /flag.txt

COPY . .

RUN useradd -m ctf
USER ctf

EXPOSE 5005

CMD ["node", "index.js"]