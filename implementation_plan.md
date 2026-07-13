# Integrate UI Enhancements and Finalize Quota UX

**Goal:** Refine navbar and hero UI for premium look, ensure responsive logo handling, and confirm quota‑exhausted modal flow.

## User Review Required

> [!IMPORTANT]
> We need the exact image assets for the updated Vizzio logo and the Tools logo (mobile version). Please provide the PNG/SVG files (preferably in `public/images/`), and confirm the single‑color hex code you’d like for the Vizzio logo background (e.g., `#2C3E50`).

> [!WARNING]
> Changing the dropdown width may affect existing layout on larger screens. Please confirm if you want the dropdown to span the full navbar width on all break‑points or only on mobile.

## Open Questions

- **Logo Assets:** What are the filenames/paths for the new Vizzio and Tools logos? Should we replace the current gradient logo with a solid‑color version?
- **Dropdown Width Preference:** Should the mega‑menu dropdown be fixed to a specific width (e.g., `max‑width: 1200px`) or stretch to the full viewport width?
- **Hero Section Spacing:** The current mobile spacing is caused by `.vd-main-content` padding (`padding-top: 62px`). Do we want to adjust this globally or only for the welcome hero?
- **Quota Modal Styling:** Is the current modal appearance acceptable, or do you want any premium styling (e.g., dark overlay, custom button colors)?

## Proposed Changes

---
### Navbar & Mobile Logos
- **File:** `resources/views/partials/navbar.blade.php`
  - Update mobile drawer logo markup to reference new logo assets.
  - Add conditional classes for mobile view (`md:hidden` vs `hidden md:block`).
  - Ensure logo links point to the home route.
- **File:** `resources/css/app.css`
  - Add/adjust CSS rules for `.navbar-logo-mobile` to control size and alignment.
  - Remove any gradient background from the Vizzio logo and apply the single‑color background.

---
### Dropdown Mega‑Menu Width
- **File:** `resources/views/partials/navbar.blade.php`
  - Modify the dropdown container classes to use `w-full` (or a custom `max-w-screen-xl`) instead of height‑based sizing.
  - Ensure the dropdown does not overflow vertically and is scrollable if content exceeds viewport height.
- **File:** `resources/css/app.css`
  - Add a rule like `.navbar-megamenu { width: 100%; max-width: 1200px; margin: 0 auto; }`.

---
### Hero Section Mobile Spacing
- **File:** `resources/views/welcome.blade.php`
  - Locate the hero wrapper and adjust the top margin/padding for mobile breakpoints.
- **File:** `resources/css/app.css`
  - Refine the media query (`@media (max-width: 480px)`) to set `padding-top: 0` for `.vd-main-content` when on the welcome page, or add a more specific selector like `.welcome-page .vd-main-content`.

---
### Quota‑Exhausted Modal Styling (Optional)
- **File:** `resources/views/layouts/app.blade.php`
  - Verify the modal markup (`#authModal`) includes premium styling (rounded corners, subtle shadow).
- **File:** `resources/css/app.css`
  - Add dark overlay and primary button color consistent with the new brand palette.

---
### Verification Plan
- **Automated Tests:** Run `php artisan test` to ensure no backend regressions.
- **Manual Checks:** 
  1. Open the site on a desktop browser, resize to mobile width, and confirm:
     - Mobile logos appear correctly.
     - Dropdown expands to full width without vertical overflow.
     - Hero section aligns directly under the navbar.
  2. Use an incognito window to trigger the guest quota (first tool click works, second click shows modal).
  3. Log in and verify the quota limit (20 uses) still triggers the same modal when exhausted.

**All changes will be committed to the existing codebase without altering existing functionality beyond the UI updates.**
