FROM node:20-alpine AS base

FROM base AS deps
RUN apk add --no-cache libc6-compat
WORKDIR /app

COPY src/package*.json ./
RUN npm ci

FROM base AS builder
WORKDIR /app
COPY --from=deps /app/node_modules ./node_modules
COPY src/ ./

RUN npm run build

FROM base AS runner
WORKDIR /app

ENV NODE_ENV=production

RUN addgroup -g 1001 ctfuser
RUN adduser -D -u 1001 -G ctfuser ctfuser

COPY --from=builder /app/public ./public
COPY --from=builder --chown=ctfuser:ctfuser /app/.next/standalone ./
COPY --from=builder --chown=ctfuser:ctfuser /app/.next/static ./.next/static
COPY flag.txt /

USER ctfuser

EXPOSE 3000

ENV PORT=3000
ENV HOSTNAME="0.0.0.0"

CMD ["node", "server.js"]