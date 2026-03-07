# Add Pincode to Address

The user wants to add a `pincode` field to the address information. This requires updating the database schema, models, and the registration logic.

## Proposed Changes

### [Database]
#### [NEW] [add_pincode_to_addresses_table.php](file:///home/ashlin/Desktop/greenhouse_main/greenhouse_main/database/migrations/2026_03_07_000000_add_pincode_to_addresses_table.php)
- Create migration to add `pincode` column to `addresses` table.

### [Models]
#### [MODIFY] [Address.php](file:///home/ashlin/Desktop/greenhouse_main/greenhouse_main/app/Models/Address.php)
- Add `pincode` to `$fillable`.

### [Actions]
#### [MODIFY] [CreateNewUser.php](file:///home/ashlin/Desktop/greenhouse_main/greenhouse_main/app/Actions/Fortify/CreateNewUser.php)
- Update validation to include `pincode` (required, string).
- Update `Address::create()` to include `pincode`.

### [Documentation]
#### [MODIFY] [postman_guide.md](file:///home/ashlin/.gemini/antigravity/brain/a7ac01e9-2720-4773-94b4-576ba51c2749/postman_guide.md)
- Update registration body example to include `pincode`.

## Verification Plan
### Automated Tests
- Run `php artisan migrate`.
- Test registration endpoint via Postman or script to ensure `pincode` is saved correctly.
