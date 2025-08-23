<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contents = [
            // Hero Section
            ['key' => 'hero_name', 'value' => 'Раца Лилия', 'section' => 'hero', 'description' => 'Psychologist name on main screen'],
            ['key' => 'hero_subtitle', 'value' => 'Психологическая поддержка и сопровождение', 'section' => 'hero', 'description' => 'Subtitle on main screen'],
            ['key' => 'hero_description', 'value' => 'Помогаю людям находить внутренние ресурсы и создавать гармонию в жизни', 'section' => 'hero', 'description' => 'Activity description'],
            ['key' => 'hero_work_mode', 'value' => 'Онлайн консультации • Индивидуальная работа', 'section' => 'hero', 'description' => 'Work mode'],
            ['key' => 'telegram_username', 'value' => 'anna_psychologist', 'section' => 'contacts', 'description' => 'Telegram username'],
            ['key' => 'whatsapp_number', 'value' => '79001234567', 'section' => 'contacts', 'description' => 'WhatsApp number'],

            // About Section
            ['key' => 'about_title', 'value' => 'Обо мне', 'section' => 'about', 'description' => 'About section title'],
            ['key' => 'about_intro_paragraph_1', 'value' => 'Я психолог с высшим образованием и многолетним опытом работы. Специализируюсь на работе с тревогой, отношениями и личностным ростом.', 'section' => 'about', 'description' => 'First about paragraph'],
            ['key' => 'about_intro_paragraph_2', 'value' => 'Моя цель — создать безопасное пространство, где вы сможете исследовать свои переживания и найти внутренние ресурсы для позитивных изменений.', 'section' => 'about', 'description' => 'Second about paragraph'],
            ['key' => 'about_credential_education', 'value' => 'Высшее психологическое образование', 'section' => 'about', 'description' => 'Education credential'],
            ['key' => 'about_credential_certificates', 'value' => 'Сертификаты КПТ, гештальт-терапия', 'section' => 'about', 'description' => 'Certificates credential'],
            ['key' => 'about_button_more', 'value' => 'Подробнее обо мне', 'section' => 'about', 'description' => 'More info button'],
            ['key' => 'about_button_diplomas', 'value' => 'Дипломы', 'section' => 'about', 'description' => 'Diplomas button'],

            // Services Section
            ['key' => 'services_title', 'value' => 'Консультации', 'section' => 'services', 'description' => 'Services section title'],
            ['key' => 'services_formats_title', 'value' => 'Форматы работы', 'section' => 'services', 'description' => 'Formats subtitle'],
            ['key' => 'services_pricing_title', 'value' => 'Стоимость', 'section' => 'services', 'description' => 'Pricing subtitle'],
            ['key' => 'services_methods_title', 'value' => 'Способы проведения', 'section' => 'services', 'description' => 'Methods subtitle'],
            ['key' => 'services_pricing_note', 'value' => 'Окончательная стоимость обсуждается индивидуально и зависит от формата и специфики запроса', 'section' => 'services', 'description' => 'Pricing note'],
            ['key' => 'services_consultation_note', 'value' => 'Первичная консультация поможет определить наиболее подходящий формат работы и выбрать методы, которые лучше всего подойдут для решения ваших задач', 'section' => 'services', 'description' => 'Consultation note'],
            ['key' => 'services_duration_label', 'value' => 'Длительность:', 'section' => 'services', 'description' => 'Duration label'],

            // Methods Section
            ['key' => 'methods_title', 'value' => 'Мой подход к терапии', 'section' => 'methods', 'description' => 'Methods section title'],
            ['key' => 'methods_subtitle', 'value' => 'Методы работы', 'section' => 'methods', 'description' => 'Methods subtitle'],
            ['key' => 'methods_principles_title', 'value' => 'Принципы работы', 'section' => 'methods', 'description' => 'Principles title'],

            // FAQ Section
            ['key' => 'faq_title', 'value' => 'Часто задаваемые вопросы', 'section' => 'faq', 'description' => 'FAQ section title'],

            // Contact Section
            ['key' => 'contact_title', 'value' => 'Записаться на консультацию', 'section' => 'contacts', 'description' => 'Contact section title'],
            ['key' => 'contact_telegram_title', 'value' => 'Telegram', 'section' => 'contacts', 'description' => 'Telegram title'],
            ['key' => 'contact_telegram_description', 'value' => 'Быстрый и удобный способ связи', 'section' => 'contacts', 'description' => 'Telegram description'],
            ['key' => 'contact_whatsapp_title', 'value' => 'WhatsApp', 'section' => 'contacts', 'description' => 'WhatsApp title'],
            ['key' => 'contact_whatsapp_description', 'value' => 'Голосовые и текстовые сообщения', 'section' => 'contacts', 'description' => 'WhatsApp description'],
            ['key' => 'contact_note_text', 'value' => 'Готова помочь вам найти внутренние ресурсы и создать гармонию в жизни', 'section' => 'contacts', 'description' => 'Contact note'],

            // Education Section
            ['key' => 'education_title', 'value' => 'Образование и квалификация', 'section' => 'education', 'description' => 'Education section title'],
            ['key' => 'education_main_title', 'value' => 'Основное образование', 'section' => 'education', 'description' => 'Main education title'],
            ['key' => 'education_additional_title', 'value' => 'Дополнительное обучение', 'section' => 'education', 'description' => 'Additional education title'],
            ['key' => 'education_certificates_button', 'value' => 'Посмотреть документы', 'section' => 'education', 'description' => 'View documents button'],

            // Certificate Gallery
            ['key' => 'certificates_modal_title', 'value' => 'Образование и сертификаты', 'section' => 'certificates', 'description' => 'Certificates modal title'],
            ['key' => 'certificates_navigation_of', 'value' => 'из', 'section' => 'certificates', 'description' => 'Navigation "of"'],
            ['key' => 'certificates_navigation_previous', 'value' => 'Предыдущий сертификат', 'section' => 'certificates', 'description' => 'Previous certificate'],
            ['key' => 'certificates_navigation_next', 'value' => 'Следующий сертификат', 'section' => 'certificates', 'description' => 'Next certificate'],
            ['key' => 'certificates_close_label', 'value' => 'Закрыть галерею', 'section' => 'certificates', 'description' => 'Close gallery'],

            // Back to Top Button
            ['key' => 'back_to_top_label', 'value' => 'Наверх', 'section' => 'ui', 'description' => 'Back to top button'],
        ];

        foreach ($contents as $content) {
            \App\Models\SiteContent::updateOrCreate(
                ['key' => $content['key']],
                $content
            );
        }

        // JSON Arrays
        $jsonContents = [
            [
                'key' => 'services_formats',
                'value' => json_encode([
                    ['name' => 'Индивидуальная консультация', 'description' => 'Работа один на один с психологом', 'duration' => '50 минут', 'price' => '3000 руб'],
                    ['name' => 'Семейная терапия', 'description' => 'Работа с парой или семьей', 'duration' => '80 минут', 'price' => '4500 руб'],
                    ['name' => 'Онлайн консультация', 'description' => 'Дистанционная работа через видеосвязь', 'duration' => '50 минут', 'price' => '2500 руб']
                ]),
                'type' => 'json',
                'section' => 'services',
                'description' => 'Consultation formats'
            ],
            [
                'key' => 'services_methods',
                'value' => json_encode([
                    ['name' => 'Очно', 'description' => 'Встречи в кабинете психолога'],
                    ['name' => 'Онлайн', 'description' => 'Видеоконференции через Zoom, Skype'],
                    ['name' => 'Телефон', 'description' => 'Консультации по телефону']
                ]),
                'type' => 'json',
                'section' => 'services',
                'description' => 'Consultation methods'
            ],
            [
                'key' => 'faq_items',
                'value' => json_encode([
                    ['question' => 'Как проходит первая консультация?', 'answer' => 'Первая встреча длится 50 минут. Мы знакомимся, обсуждаем ваш запрос и определяем дальнейший план работы.'],
                    ['question' => 'Сколько нужно сессий?', 'answer' => 'Количество встреч индивидуально. Обычно заметные изменения происходят через 5-10 сессий.'],
                    ['question' => 'Конфиденциальность гарантирована?', 'answer' => 'Да, вся информация строго конфиденциальна согласно этическому кодексу психолога.']
                ]),
                'type' => 'json',
                'section' => 'faq',
                'description' => 'FAQ items'
            ]
        ];

        foreach ($jsonContents as $content) {
            \App\Models\SiteContent::updateOrCreate(
                ['key' => $content['key']],
                $content
            );
        }
    }
}
