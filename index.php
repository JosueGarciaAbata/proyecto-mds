<!DOCTYPE html>
<!--
* Tabler - Premium and Open Source dashboard template with responsive and high quality UI.
* @version 1.0.0-beta19
* @link https://tabler.io
* Copyright 2018-2023 The Tabler Authors
* Copyright 2018-2023 codecalm.net Paweł Kuna
* Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
-->
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, viewport-fit=cover"
    />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>
      Dashboard - Tabler - Premium and Open Source dashboard template with
      responsive and high quality UI.
    </title>
    <!-- CSS files -->
    <link href="./dist/css/tabler.min.css?1684106062" rel="stylesheet" />
    <link href="./dist/css/tabler-flags.min.css?1684106062" rel="stylesheet" />
    <link
      href="./dist/css/tabler-payments.min.css?1684106062"
      rel="stylesheet"
    />
    <link
      href="./dist/css/tabler-vendors.min.css?1684106062"
      rel="stylesheet"
    />
    <link href="./dist/css/demo.min.css?1684106062" rel="stylesheet" />
    <style>
      @import url("https://rsms.me/inter/inter.css");
      :root {
        --tblr-font-sans-serif: "Inter Var", -apple-system, BlinkMacSystemFont,
          San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
      }
      body {
        font-feature-settings: "cv03", "cv04", "cv11";
      }
    </style>
  </head>
  <body>
    <script src="./dist/js/demo-theme.min.js?1684106062"></script>
    <div class="page">
      <!-- Navbar -->
      <header class="navbar navbar-expand-md d-print-none">
        <div class="container-xl">
          <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbar-menu"
            aria-controls="navbar-menu"
            aria-expanded="false"
            aria-label="Toggle navigation"
          >
            <span class="navbar-toggler-icon"></span>
          </button>
          <h1
            class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3"
          >
            <a href=".">
              <img
                src="./static/logo.svg"
                width="110"
                height="32"
                alt="Tabler"
                class="navbar-brand-image"
              />
            </a>
          </h1>
          <div class="navbar-nav flex-row order-md-last">
            <div class="nav-item d-none d-md-flex me-3">
              <div class="btn-list"></div>
            </div>
            <div class="d-none d-md-flex">
              <a
                href="?theme=dark"
                class="nav-link px-0 hide-theme-dark"
                title="Enable dark mode"
                data-bs-toggle="tooltip"
                data-bs-placement="bottom"
              >
                <!-- Download SVG icon from http://tabler-icons.io/i/moon -->
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="icon"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                  stroke-width="2"
                  stroke="currentColor"
                  fill="none"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                >
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path
                    d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z"
                  />
                </svg>
              </a>
              <a
                href="?theme=light"
                class="nav-link px-0 hide-theme-light"
                title="Enable light mode"
                data-bs-toggle="tooltip"
                data-bs-placement="bottom"
              >
                <!-- Download SVG icon from http://tabler-icons.io/i/sun -->
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="icon"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                  stroke-width="2"
                  stroke="currentColor"
                  fill="none"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                >
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                  <path
                    d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7"
                  />
                </svg>
              </a>
            </div>
          </div>
        </div>
      </header>
      <header class="navbar-expand-md">
        <div class="collapse navbar-collapse" id="navbar-menu">
          <div class="navbar">
            <div class="container-xl">
              <!-- Parte inferior -->
              <ul class="navbar-nav">
                <li class="nav-item active">
                  <a class="nav-link" href="./">
                    <span class="nav-link-icon d-md-none d-lg-inline-block"
                      ><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="icon"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                        stroke="currentColor"
                        fill="none"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                      >
                        <path
                          stroke="none"
                          d="M0 0h24v24H0z"
                          fill="none"
                        ></path>
                        <path
                          d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"
                        ></path>
                        <path
                          d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"
                        ></path>
                        <path d="M15 15l3.35 3.35"></path>
                        <path d="M9 15l-3.35 3.35"></path>
                        <path d="M5.65 5.65l3.35 3.35"></path>
                        <path d="M18.35 5.65l-3.35 3.35"></path>
                      </svg>
                    </span>
                    <span class="nav-link-title">Register</span>
                  </a>
                </li>

                <li class="nav-item active">
                  <a class="nav-link" href="./">
                    <span class="nav-link-icon d-md-none d-lg-inline-block"
                      ><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="icon"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                        stroke="currentColor"
                        fill="none"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                      >
                        <path
                          stroke="none"
                          d="M0 0h24v24H0z"
                          fill="none"
                        ></path>
                        <path
                          d="M5 11a7 7 0 0 1 14 0v7a1.78 1.78 0 0 1 -3.1 1.4a1.65 1.65 0 0 0 -2.6 0a1.65 1.65 0 0 1 -2.6 0a1.65 1.65 0 0 0 -2.6 0a1.78 1.78 0 0 1 -3.1 -1.4v-7"
                        ></path>
                        <path d="M10 10l.01 0"></path>
                        <path d="M14 10l.01 0"></path>
                        <path d="M10 14a3.5 3.5 0 0 0 4 0"></path>
                      </svg>
                    </span>
                    <span class="nav-link-title">Login</span>
                  </a>
                </li>
                <li class="nav-item active">
                  <a class="nav-link" href="pato.html">
                    <span class="nav-link-icon d-md-none d-lg-inline-block"
                      ><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="icon"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                        stroke="currentColor"
                        fill="none"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                      >
                        <path
                          stroke="none"
                          d="M0 0h24v24H0z"
                          fill="none"
                        ></path>
                        <path
                          d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"
                        ></path>
                      </svg>
                    </span>
                    <span class="nav-link-title">Discover</span>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </header>

      <div
        class="page-wrapper d-flex justify-content-center align-items-center"
      >
        <div class="container container-tight py-4">
         
          <div class="card card-md">
            <div class="card-body">
              <h2 class="h2 text-center mb-4">Login to your account</h2>
              <form action="./" method="get" autocomplete="off" novalidate="">
                <div class="mb-3">
                  <label class="form-label">Email address</label>
                  <input
                    type="email"
                    class="form-control"
                    placeholder="your@email.com"
                    autocomplete="off"
                  />
                </div>
                <div class="mb-2">
                  <label class="form-label">
                    Password
                    <span class="form-label-description">
                      <a href="./forgot-password.html">I forgot password</a>
                    </span>
                  </label>
                  <div class="input-group input-group-flat">
                    <input
                      type="password"
                      class="form-control"
                      placeholder="Your password"
                      autocomplete="off"
                    />
                    <span class="input-group-text">
                      <a
                        href="#"
                        class="link-secondary"
                        data-bs-toggle="tooltip"
                        aria-label="Show password"
                        data-bs-original-title="Show password"
                        ><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          class="icon"
                          width="24"
                          height="24"
                          viewBox="0 0 24 24"
                          stroke-width="2"
                          stroke="currentColor"
                          fill="none"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                        >
                          <path
                            stroke="none"
                            d="M0 0h24v24H0z"
                            fill="none"
                          ></path>
                          <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                          <path
                            d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"
                          ></path>
                        </svg>
                      </a>
                    </span>
                  </div>
                </div>
                <div class="mb-2">
                  <label class="form-check">
                    <input type="checkbox" class="form-check-input" />
                    <span class="form-check-label"
                      >Remember me on this device</span
                    >
                  </label>
                </div>
                <div class="form-footer">
                  <button type="submit" class="btn btn-primary w-100">
                    Sign in
                  </button>
                </div>
              </form>
            </div>
            <div class="hr-text">or</div>
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <a href="#" class="btn w-100">
                    <!-- Download SVG icon from http://tabler-icons.io/i/brand-github -->
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="icon text-github"
                      width="24"
                      height="24"
                      viewBox="0 0 24 24"
                      stroke-width="2"
                      stroke="currentColor"
                      fill="none"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    >
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                      <path
                        d="M9 19c-4.3 1.4 -4.3 -2.5 -6 -3m12 5v-3.5c0 -1 .1 -1.4 -.5 -2c2.8 -.3 5.5 -1.4 5.5 -6a4.6 4.6 0 0 0 -1.3 -3.2a4.2 4.2 0 0 0 -.1 -3.2s-1.1 -.3 -3.5 1.3a12.3 12.3 0 0 0 -6.2 0c-2.4 -1.6 -3.5 -1.3 -3.5 -1.3a4.2 4.2 0 0 0 -.1 3.2a4.6 4.6 0 0 0 -1.3 3.2c0 4.6 2.7 5.7 5.5 6c-.6 .6 -.6 1.2 -.5 2v3.5"
                      ></path>
                    </svg>
                    Login with Github
                  </a>
                </div>
                <div class="col">
                  <a href="#" class="btn w-100">
                    <!-- Download SVG icon from http://tabler-icons.io/i/brand-twitter -->
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="icon text-twitter"
                      width="24"
                      height="24"
                      viewBox="0 0 24 24"
                      stroke-width="2"
                      stroke="currentColor"
                      fill="none"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    >
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                      <path
                        d="M22 4.01c-1 .49 -1.98 .689 -3 .99c-1.121 -1.265 -2.783 -1.335 -4.38 -.737s-2.643 2.06 -2.62 3.737v1c-3.245 .083 -6.135 -1.395 -8 -4c0 0 -4.182 7.433 4 11c-1.872 1.247 -3.739 2.088 -6 2c3.308 1.803 6.913 2.423 10.034 1.517c3.58 -1.04 6.522 -3.723 7.651 -7.742a13.84 13.84 0 0 0 .497 -3.753c0 -.249 1.51 -2.772 1.818 -4.013z"
                      ></path>
                    </svg>
                    Login with Twitter
                  </a>
                </div>
              </div>
            </div>
          </div>
          <div class="text-center text-muted mt-3">
            Don't have account yet?
            <a href="./sign-up.html" tabindex="-1">Sign up</a>
          </div>
        </div>
      </div>
    </div>
   
    </div>
    <!-- Libs JS -->
    <script
      src="./dist/libs/apexcharts/dist/apexcharts.min.js?1684106062"
      defer
    ></script>
    <script
      src="./dist/libs/jsvectormap/dist/js/jsvectormap.min.js?1684106062"
      defer
    ></script>
    <script
      src="./dist/libs/jsvectormap/dist/maps/world.js?1684106062"
      defer
    ></script>
    <script
      src="./dist/libs/jsvectormap/dist/maps/world-merc.js?1684106062"
      defer
    ></script>
    <!-- Tabler Core -->
    <script src="./dist/js/tabler.min.js?1684106062" defer></script>
    <script src="./dist/js/demo.min.js?1684106062" defer></script>
  </body>
</html>
