# Logo Images Directory

This directory contains logo and image assets for the BSU-Bokod SIMS application.

## Required Logo

### Main Logo: `bsu-logo.png`
- **Location**: Place your BSU-Bokod logo file here as `bsu-logo.png`
- **Recommended dimensions**: 512x512 pixels (square) or 512x400 pixels (rectangular)
- **Format**: PNG with transparent background (preferred) or white background
- **Used in**:
  - Landing page (welcome.blade.php)
  - Admin login page
  - Student login pages

### Image Specifications

**Optimal settings for best display:**
- Resolution: 512x512px minimum (for retina displays)
- Format: PNG-24 (for transparency support)
- File size: Keep under 200KB for fast loading
- Background: Transparent or white

### How to Add Your Logo

1. Save your BSU-Bokod logo as `bsu-logo.png`
2. Place it in this directory: `public/images/bsu-logo.png`
3. The system will automatically detect and display it
4. If no logo exists, a placeholder will be shown

### Additional Logos (Optional)

You can also add variant logos for different contexts:

- `bsu-logo-white.png` - White version for dark backgrounds
- `bsu-logo-horizontal.png` - Horizontal layout variant
- `bsu-logo-icon.png` - Icon-only version (for favicons)

### Testing

After adding your logo, visit:
- Landing page: `http://localhost:8000/`
- Admin login: `http://localhost:8000/admin/login`
- Student login: `http://localhost:8000/student/login`

The logo should appear centered at the top of each page.

### Troubleshooting

**Logo not showing?**
1. Verify filename is exactly `bsu-logo.png` (case-sensitive on Linux)
2. Check file is in `public/images/` directory
3. Clear browser cache (Ctrl+Shift+R)
4. Run `php artisan cache:clear` if needed

**Logo looks blurry?**
- Use higher resolution (2x or 3x size)
- Ensure PNG format (not JPG)
- Check image quality during export

**Logo too large/small?**
- Edit the CSS classes in the Blade templates
- Current size: `w-32 h-32` (128x128px display size)
- Modify in `welcome.blade.php` line 25
