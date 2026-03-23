# Validation Lock for GLPI

[![License: GPL v3](https://img.shields.io/badge/License-GPLv3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)
[![GLPI: >=11.0](https://img.shields.io/badge/GLPI-%3E%3D11.0-orange.svg)](https://glpi-project.org/)

**Validation Lock** is a professional plugin for GLPI designed to enforce data integrity and improve the ticket resolution workflow. It prevents tickets from being resolved or closed, and blocks the addition of solutions, as long as there are pending validations.

---

## 🚀 Key Features

- 🛑 **Block Resolution**: Prevent status change to "Solved" or "Closed" if any validation is in "Pending" status.
- 🚫 **Block Solutions**: Prevent adding new solutions to a ticket with active validation requests.
- 💬 **Clear Feedback**: Provides clear error messages to users in their preferred language.
- ⚡ **Backend Enforcement**: Validations are performed on the server side for maximum security.
- 🌍 **Internationalization**: Fully translated into English (en_GB) and Spanish (es_ES).

## 📋 Requirements

- **GLPI** >= 11.0.0
- **PHP** >= 8.1 (standard for GLPI 11)

## 🛠️ Installation

1. Download the latest release from [GitHub Releases](https://github.com/JuanCarlosAcostaPeraba/glpi-validationlock-plugin/releases).
2. Extract the archive into your GLPI `plugins/` directory.
3. Ensure the directory is named `validationlock`.
4. Go to **Setup > Plugins** in your GLPI instance.
5. Click **Install** and then **Activate**.

> [!NOTE]
> The installation process will attempt to compile the translation files (`.mo`) automatically if `msgfmt` is available on your server.

## 🏛️ Marketplace Compliance

This plugin follows official GLPI development guidelines:
- No core modifications.
- Uses GLPI Hooks system (`pre_item_update`, `pre_item_add`).
- Secure database queries using `$DB->request()`.
- CSRF compliance.
- Structured metadata (`plugin.xml`).

## 📄 License

This plugin is licensed under the [GPLv3+](LICENSE).

---
Developed for real-world usage at **Hospital Universitario de Canarias (HUC)**.
Maintained by **Juan Carlos Acosta Perabá**.
