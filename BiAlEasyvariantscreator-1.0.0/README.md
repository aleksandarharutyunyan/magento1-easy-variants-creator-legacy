# Magento 1 EasyVariantsCreator – Admin Tool for Product Variant Creation

**EasyVariantsCreator** is an advanced Magento 1 admin module that simplifies the creation and management of product variants (e.g., configurable products) from existing simple products or templates.

## 🧩 Features

- Full Magento 1 admin CRUD interface
- Create, edit, delete and list variant creation tasks
- Mass creation of simple products for variant combinations
- Product Grid integration and mass actions
- Export tasks to CSV and Excel XML
- Visual indicators (custom icons) in admin grid
- Uses Magento's layout, block, and model systems
- Cron-ready logic (via `Model/Cron.php`)

---

## ⚙️ Compatibility

- Magento **Community Edition 1.7 – 1.9.x**

## 📁 Module Structure

Includes:
- Admin controllers
- Form blocks and tabs
- Model, resource model, collections
- Install script with custom DB table
- Admin layout XML + templates
- Helper and observer logic

---

## 🚀 Installation

Place contents into your Magento root and run:

```bash
php shell/indexer.php reindexall
php bin/magento cache:flush
```

Admin menu: `Catalog > EasyVariantsCreator`

---

## 📦 Legacy Notice

This module was originally developed for internal use or commercial distribution during the Magento 1 era.  
It is now released for **educational and demonstration purposes**.  
No active support is provided.

---

## 📘 License

Released under the **MIT license** – use, modify and distribute freely.
