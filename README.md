ChakaNoks - CI4 Migrations & Seeders Bundle

What you get:
- Migrations for core SCMS tables (branches, suppliers, products, stock_batches, purchase_requests, etc.)
- Seeders: BranchSeeder, SupplierSeeder, ProductSeeder, UserSeeder
- Roles used in users are: superadmin, central_admin, branch_manager, inventory_staff, logistics_coordinator, franchise_manager
  (Supplier & System Administrator (IT) are NOT included.)

Important: If you are using CodeIgniter Shield, do NOT run CreateUsers migration or UserSeeder (Shield creates its own user tables). Instead configure Shield groups and create users with Shield.

Installation steps (copy+run):
1) Copy the files into your CI4 project root so they appear in:
   app/Database/Migrations/
   app/Database/Seeds/

   Example from the project root:
   unzip chakanoks_migrations_seeders.zip -d .

2) Configure database in .env and create the database:
   mysql -u root -p -e "CREATE DATABASE chakanoks_scms;"

3) Run migrations:
   php spark migrate

   If you also want package migrations (e.g., Shield), run:
   php spark migrate --all

4) Run seeders in this order:
   php spark db:seed BranchSeeder
   php spark db:seed SupplierSeeder
   php spark db:seed ProductSeeder
   php spark db:seed UserSeeder   # SKIP if using Shield

5) Verify: open phpMyAdmin or use MySQL CLI to confirm tables & data.
   Example users seeded:
     superadmin@chakanoks.test / SuperPass123!
     central@chakanoks.test  / Central123!
     matina.manager@chakanoks.test / Branch123!

If you want, I can also:
- Convert your index.php login/register UI into CI4 view and wire the Auth controller.
- Generate basic Controllers (Inventory, Purchasing) that use these tables.
