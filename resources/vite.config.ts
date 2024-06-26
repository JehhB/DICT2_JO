import { defineConfig, splitVendorChunkPlugin } from "vite";
import vue from "@vitejs/plugin-vue";
import liveReload from "vite-plugin-live-reload";
import path from "node:path";

export default defineConfig({
  plugins: [
    vue(),
    liveReload([
      __dirname + "/(app|config|views)/**/*.php",
      __dirname + "/../public/**/*.{css,php,html,js}",
    ]),
    splitVendorChunkPlugin(),
  ],

  root: "src",
  base: process.env.APP_ENV === "development" ? "/" : "/dist/",

  build: {
    outDir: "../../public/dist",
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: path.resolve(__dirname, "src/main.ts"),
    },
  },

  server: {
    strictPort: true,
    port: 5133,
  },

  resolve: {
    alias: {
      vue: "vue/dist/vue.esm-bundler.js",
    },
  },
});
