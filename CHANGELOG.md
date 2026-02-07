# CHANGELOG

## v0.2.0 — معاملات، Slug، علاقات، شريط علوي، اختبارات
- إضافة Trait: HasSlug وتطبيقه في Service و Project وإزالة التكرار.
- تطبيق DB::transaction في ServiceController و ProjectController (store/update).
- تحسين تحديث علاقة المشاريع للخدمة بتحديث تفاضلي بدل حذف شامل.
- تحسين الشريط العلوي في لوحة الإدارة: روابط رئيسية، عداد إشعارات، تأكيد الخروج، دعم RTL.
- إضافة اختبارات وحدة للـ Slug.

## v0.1.x — بنية أساسية
- إنشاء جداول services, projects مع فهارس فريدة على slug.
- إضافة عمود service_id للمشاريع مع onDelete('cascade').
