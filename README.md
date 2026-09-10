# Laravel + Sheaf UI Starter Kit

Personal Laravel starter kit dengan Sheaf UI component library, Tailwind CSS v4, Livewire, dan authentication scaffolding.

## Tech Stack

- **Laravel 13.x** — PHP 8.3+
- **Sheaf UI** — Component library (button, sidebar, navlist, badge, brand, navbar, avatar, dropdown, theme-switcher, kbd)
- **Tailwind CSS v4** — Utility-first CSS
- **Livewire 4.4** — Reactive components
- **Alpine.js** — Lightweight JavaScript
- **Vite 8** — Build tool

## Features

- Authentication (login, register, forgot/reset password, email verification)
- Profile management (edit profile, update password, delete account)
- Responsive sidebar layout
- Dark mode support
- Sheaf UI components throughout

## Setup

```bash
# Clone
git clone <repo-url>
cd laravel-13.x

# Install dependencies
composer install
npm install

# Environment
cp .env.example .env
php artisan key:generate

# Database
php artisan migrate

# Build assets
npm run build

# Serve
php artisan serve
```

## Available Sheaf UI Components

- `<x-ui.button>` — Button (primary, outline, ghost, danger variants)
- `<x-ui.sidebar>` — Sidebar layout
- `<x-ui.navlist>` — Navigation list
- `<x-ui.navbar>` — Top navbar
- `<x-ui.brand>` — Brand/logo
- `<x-ui.badge>` — Badge
- `<x-ui.avatar>` — Avatar
- `<x-ui.dropdown>` — Dropdown menu
- `<x-ui.theme-switcher>` — Dark/light mode toggle
- `<x-ui.kbd>` — Keyboard shortcut display
- `<x-ui.icon>` — Icon component

## Structure

```
resources/
├── views/
│   ├── auth/           # Authentication views
│   ├── components/     # Breeze + Sheaf UI components
│   │   └── ui/         # Sheaf UI component library
│   ├── layouts/        # App & guest layouts
│   └── profile/        # Profile management
├── css/
│   ├── app.css         # Tailwind v4 entry
│   └── theme.css       # Sheaf UI theme tokens
└── js/
    └── app.js          # Alpine.js + Livewire
```

## License

MIT
