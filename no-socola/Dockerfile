FROM python:3.11-alpine

LABEL author="winky"

WORKDIR /app

COPY requirements.txt .

RUN apk add --no-cache gcc musl-dev libffi-dev \
    && pip install --no-cache-dir -r requirements.txt \
    && apk del gcc musl-dev libffi-dev

COPY ./src/ .

CMD ["python", "app.py"]