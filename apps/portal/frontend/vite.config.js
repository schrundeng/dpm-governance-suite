import { defineConfig } from "vite";

export default defineConfig({
  base: "/",
  server: {
    allowedHosts: true,
    host: true,
    port: 3000,
    proxy: {
      "/api": {
        target: "http://localhost:8000",
        changeOrigin: true
      },
      "/admin": {
        target: "http://localhost:8000",
        changeOrigin: true
      }
    }
  }
});
