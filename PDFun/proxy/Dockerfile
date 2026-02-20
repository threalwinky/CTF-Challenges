FROM python:3.11-slim

WORKDIR /proxy

COPY app.py .
COPY requirements.txt .
COPY flag.txt /

RUN pip install --no-cache-dir -r requirements.txt

CMD ["python3", "app.py"]