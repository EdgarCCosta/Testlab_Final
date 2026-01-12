FROM node:20-alpine

# Directorio de trabajo dentro del contenedor
WORKDIR /app

# Copiar solo la carpeta del frontend
COPY testlab/frontend/ ./

# Instalar dependencias y build
RUN npm install && npm run build

# Servir la SPA
CMD npx serve -s dist/testlab/browser -l $PORT