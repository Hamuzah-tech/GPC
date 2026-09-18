<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\GalleryImage;
use App\Models\Leader;
use App\Models\News;
use App\Models\Page;
use App\Models\Pillar;
use App\Models\Program;
use App\Models\Setting;
use App\Models\StrategicApproach;
use App\Models\ThematicArea;
use Illuminate\Database\Seeder;

class WebsiteSeeder extends Seeder
{
    public function run(): void
    {
        $this->settings();
        $this->pages();
        $this->leaders();
        $this->programs();
        $this->pillars();
        $this->thematicAreas();
        $this->strategicApproaches();
        $this->faqs();
        $this->gallery();
        $this->news();
    }

    private function settings(): void
    {
        $values = [
            'site_name' => 'GPC',
            'tagline' => 'Empowering Adolescent Girls & Young Women in Malawi',
            'about_footer' => 'Empowering Adolescent Girls and Young Women in Malawi to become agents of change for economic and social development.',
            'address' => 'Lilongwe District, Malawi',
            'phone' => '+265 881 234 567',
            'email' => 'info@girlpowerforchange.org',
            'facebook_url' => 'https://facebook.com/gpcmalawi',
            'instagram_url' => 'https://instagram.com/gpcmalawi',
            'impact_districts' => '3',
            'impact_programs' => '5',
            'impact_lives' => '10000',
            'office_hours' => 'Mon–Fri, 8:00 AM – 5:00 PM',
        ];

        foreach ($values as $key => $value) {
            Setting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }

    private function pages(): void
    {
        $pages = [
            [
                'slug' => 'home-about',
                'title' => 'About GPC',
                'subtitle' => 'Our Impact',
                'body' => "GPC is a women-led organization focused on addressing the diverse needs of Adolescent Girls and Young Women (AGYW) in Malawi. We create a supportive environment for their development through sectoral programs in Lilongwe, Zomba, and Dowa Districts.\n\nOur mission is to empower AGYW through comprehensive programs that address gender equality, education, health, economic empowerment, climate action, and leadership development.",
                'extra' => 'Your support can help us reach more adolescent girls and young women in Malawi with life-changing programs that create sustainable impact.',
            ],
            [
                'slug' => 'mission',
                'title' => 'Our Mission',
                'subtitle' => 'Driving meaningful change for adolescent girls and young women',
                'body' => 'Supporting Adolescent Girls and Young Women (AGYW) to become active citizens and individuals who can influence and challenge the status quo in addressing their diverse needs.',
                'extra' => 'Through targeted programs and advocacy, we equip AGYW with the skills, knowledge, and confidence to shape their own futures and transform their communities.',
            ],
            [
                'slug' => 'vision',
                'title' => 'Our Vision',
                'subtitle' => 'Imagining a brighter future for every adolescent girl and young woman in Malawi',
                'body' => 'A Malawian society where adolescent girls and young women are empowered and supported to become agents of change for both economic and social development.',
                'extra' => 'We envision a future where every AGYW in Malawi has the opportunity, resources, and support to reach her full potential and contribute meaningfully to her community.',
            ],
        ];

        foreach ($pages as $page) {
            Page::query()->updateOrCreate(['slug' => $page['slug']], $page);
        }
    }

    private function leaders(): void
    {
        $leaders = [
            [
                'name' => 'Ms Linda Phiri',
                'position' => 'Executive Director',
                'bio_preview' => 'Linda Phiri is an award-winning youth advocate, policy influencer, and transformative leader advancing gender equality, youth empowerment, and SRHR in Malawi and across Africa.',
                'full_bio' => "Linda Phiri is an award-winning youth advocate, policy influencer, and transformative leader advancing gender equality, youth empowerment, and sexual and reproductive health and rights (SRHR) in Malawi and across Africa. Recognized among Malawi's Inspiring 100 Women, she is known for championing meaningful youth participation, amplifying the voices of adolescent girls and young women, and driving impactful social change through advocacy, leadership, and community mobilization. Currently pursuing a Bachelor's Degree in Education (Social Studies) at the University of Malawi, Linda continues to bridge grassroots activism with national, regional, and global policy engagement.\n\nShe serves as the Youth and Adolescent Girls and Young Women Representative on the Malawi Global Fund Country Coordinating Mechanism (MGF-CCM), Her Voice Fund Ambassador, National Focal Point for Women for Global Fund Malawi, and Steering Committee Member for the UNITED! Movement. A former Vice President of the University of Malawi Students' Representative Council and former Assistant Coordinator at YouLead Africa, Linda is widely recognized for her strategic leadership, diplomacy, and ability to influence systems, mobilize communities, and build partnerships that advance equity and sustainable development.",
                'image' => 'images/leadership/leader1.jpg',
                'sort_order' => 1,
            ],
            [
                'name' => 'Ms Monica Mulwafu',
                'position' => 'Programmes Manager',
                'bio_preview' => 'Monica Mulwafu is a Gender and Development Advocate and researcher with experience in administration, youth empowerment, climate change, and SGBV.',
                'full_bio' => 'Monica Mulwafu is a Gender and Development Advocate and researcher with experience in administration, youth empowerment, climate change, and sexual and gender-based violence (SGBV). She holds a Bachelor of Science degree in Gender and Development with Credit from the Lilongwe University of Agriculture and Natural Resources (LUANAR) and has worked with youth-focused and community-based organizations in Malawi.',
                'image' => 'images/leadership/leader2.jpg',
                'sort_order' => 2,
            ],
            [
                'name' => 'Ms Jane Kanyangale',
                'position' => 'Partnership & Sponsorship Coordinator',
                'bio_preview' => "Jane coordinates strategic partnerships and sponsorship initiatives to expand GPC's reach and impact across Malawi.",
                'full_bio' => "Jane Kanyangale serves as the Partnership & Sponsorship Coordinator at GirlPower for Change, where she builds and maintains relationships with donors, corporate partners, and funding organizations. Her work ensures sustainable resource mobilization for GPC's programs across Lilongwe, Zomba, and Dowa Districts.",
                'image' => 'images/leadership/leader3.jpeg',
                'sort_order' => 3,
            ],
            [
                'name' => 'Ms Mervis Kambala',
                'position' => 'Administrative Officer',
                'bio_preview' => 'Mervis Kambala is a physiotherapy intern and administrative officer, passionate about healthcare, community support, and organizational development.',
                'full_bio' => 'Mervis Kambala is a physiotherapy intern and administrative officer at GirlPower for Change, passionate about healthcare, community support, and organizational development. Dedicated and professional, Mervis combines clinical knowledge with strong administrative skills to make a positive impact in both healthcare and community service.',
                'image' => 'images/about.jpg',
                'sort_order' => 4,
            ],
            [
                'name' => 'Ms Yankho Phiri',
                'position' => 'Communications Officer',
                'bio_preview' => "Yankho is passionate about girls' rights advocacy, youth empowerment, and community development, with experience in SRHR, mental health, and gender equality.",
                'full_bio' => "I am passionate about girls' rights advocacy, youth empowerment, and community development, with experience in Sexual and Reproductive Health and Rights (SRHR), mental health, and gender equality initiatives. Skilled in community mobilization, workshop facilitation, and advocacy campaigns, I am committed to creating meaningful social impact and empowering young people, especially girls and young women.\n\nAs an aspiring youth leader, I volunteer with youth-led organisations focused on empowerment and social change, using my skills and passion to contribute to sustainable community development. At 22, I look forward to expanding my impact through leadership, advocacy, and service.",
                'image' => 'images/leadership/leader5.jpg',
                'sort_order' => 5,
            ],
            [
                'name' => 'Ms Tontho Nkhata',
                'position' => 'Field Officer',
                'bio_preview' => "Tontho supports the design and implementation of GPC's programs, working directly with AGYW in target communities.",
                'full_bio' => 'Tontho Nkhata serves as Field Officer at GirlPower for Change, supporting the implementation of programs across education, health, and economic empowerment. She works closely with community partners to ensure effective delivery of services to adolescent girls and young women in Lilongwe, Zomba, and Dowa Districts.',
                'image' => 'images/volunteer.jpg',
                'sort_order' => 6,
            ],
            [
                'name' => 'Mr James Hamuzah',
                'position' => 'Head of ICT',
                'bio_preview' => "James leads GPC's digital transformation and technology initiatives to enhance program delivery and data management.",
                'full_bio' => "James Hamuzah serves as the Head of ICT at GirlPower for Change, overseeing technology infrastructure, digital platforms, and data systems that support GPC's programs. He ensures efficient data collection, reporting, and digital outreach to amplify the organization's impact across Malawi.",
                'image' => 'images/leadership/leader7.jpg',
                'sort_order' => 7,
            ],
        ];

        foreach ($leaders as $leader) {
            Leader::query()->updateOrCreate(['name' => $leader['name']], $leader);
        }
    }

    private function programs(): void
    {
        $programs = [
            ['title' => 'Gender and Development', 'description' => 'Promoting gender equality and women\'s empowerment through community education, policy advocacy, and leadership training.', 'image' => 'images/about.jpg', 'sort_order' => 1],
            ['title' => 'Health (Mental Health)', 'description' => 'Addressing mental health issues affecting AGYW through education, counseling, and access to healthcare services.', 'image' => 'images/image12.jpg', 'sort_order' => 2],
            ['title' => 'Girls Education', 'description' => 'Advocating for and supporting girls\' access to quality education through scholarships and school reintegration programs.', 'image' => 'images/image15.jpg', 'sort_order' => 3],
            ['title' => 'Economic Empowerment', 'description' => 'Providing skills and opportunities for AGYW to achieve economic independence through training and microfinance.', 'image' => 'images/news.jpeg', 'sort_order' => 4],
            ['title' => 'Climate Change', 'description' => 'Engaging AGYW in climate action through education, sustainable agriculture training, and conservation projects.', 'image' => 'images/image12.jpg', 'sort_order' => 5],
            ['title' => 'Youth Leadership', 'description' => 'Developing leadership skills among young women through workshops, mentorship programs, and community engagement.', 'image' => 'images/about_hero.jpg', 'sort_order' => 6],
        ];

        foreach ($programs as $program) {
            Program::query()->updateOrCreate(['title' => $program['title']], $program);
        }
    }

    private function pillars(): void
    {
        $pillars = [
            ['title' => 'Partnership', 'description' => 'Collaborating with stakeholders to maximize impact and create sustainable change for AGYW.', 'sort_order' => 1],
            ['title' => 'Advocacy', 'description' => 'Amplifying the voices of AGYW in policy discussions and public discourse.', 'sort_order' => 2],
            ['title' => 'Shared Learning', 'description' => 'Creating spaces for knowledge exchange and capacity building among AGYW and stakeholders.', 'sort_order' => 3],
            ['title' => 'Social & Economic Justice', 'description' => 'Promoting equitable access to resources and opportunities for all AGYW.', 'sort_order' => 4],
        ];

        foreach ($pillars as $pillar) {
            Pillar::query()->updateOrCreate(['title' => $pillar['title']], $pillar);
        }
    }

    private function thematicAreas(): void
    {
        $areas = [
            ['title' => 'Gender and Development', 'description' => 'Promoting gender equality and women\'s empowerment through community education, policy advocacy, and leadership training.', 'sort_order' => 1],
            ['title' => 'Health (SRHR, Mental Health)', 'description' => 'Addressing sexual and reproductive health rights and mental health issues affecting AGYW through education, counseling, and access to healthcare services.', 'sort_order' => 2],
            ['title' => 'Girls\' Education', 'description' => 'Advocating for and supporting girls\' access to quality education through scholarships, mentorship, and school reintegration programs.', 'sort_order' => 3],
            ['title' => 'Economic Empowerment', 'description' => 'Providing skills and opportunities for AGYW to achieve economic independence through vocational training, entrepreneurship, and microfinance.', 'sort_order' => 4],
            ['title' => 'Climate Change', 'description' => 'Engaging AGYW in climate action through education, sustainable agriculture training, and environmental conservation projects.', 'sort_order' => 5],
        ];

        foreach ($areas as $area) {
            ThematicArea::query()->updateOrCreate(['title' => $area['title']], $area);
        }
    }

    private function strategicApproaches(): void
    {
        $items = [
            ['title' => 'Creativity & Innovation', 'description' => 'We employ creative approaches and innovative solutions to address the unique challenges facing AGYW in Malawi. Our programs are designed to engage young women through methods that resonate with their experiences and aspirations.', 'sort_order' => 1],
            ['title' => 'Advocacy & Awareness', 'description' => 'We advocate for policy changes and raise awareness about issues affecting AGYW at local, regional, and national levels. Our advocacy work amplifies the voices of young women in decision-making spaces.', 'sort_order' => 2],
            ['title' => 'Partnerships & Collaboration', 'description' => 'We build strategic partnerships with government agencies, NGOs, community organizations, and the private sector to maximize our impact and reach more AGYW with sustainable solutions.', 'sort_order' => 3],
        ];

        foreach ($items as $item) {
            StrategicApproach::query()->updateOrCreate(['title' => $item['title']], $item);
        }
    }

    private function faqs(): void
    {
        $faqs = [
            ['question' => 'How can I volunteer with GPC?', 'answer' => 'You can volunteer by filling out our volunteer registration form on the Volunteer page. We welcome individuals with skills in mentoring, education, health, fundraising, and administration. After submitting your application, our team will contact you within 5-7 business days.', 'sort_order' => 1],
            ['question' => 'What types of partnerships does GPC engage in?', 'answer' => 'We partner with government agencies, NGOs, community-based organizations, private sector companies, and international donors. Partnerships focus on resource mobilization, technical expertise, joint program implementation, and advocacy campaigns.', 'sort_order' => 2],
            ['question' => 'How can I donate to support GPC\'s work?', 'answer' => 'You can donate via our secure online platform (coming soon) or contact us directly for bank transfer details. Donations support scholarships, health services, economic empowerment training, and climate action projects.', 'sort_order' => 3],
            ['question' => 'How does GPC select beneficiaries for its programs?', 'answer' => 'Beneficiaries are selected through community-based targeting, school referrals, and needs assessments. We prioritize marginalized adolescent girls and young women aged 14–25 in Lilongwe, Zomba, and Dowa Districts.', 'sort_order' => 4],
            ['question' => 'Can organizations request GPC to implement programs in their communities?', 'answer' => 'Yes! We welcome collaboration requests from CBOs, schools, faith-based organizations, and local government. Please reach out via email or the contact form with details about your community and needs.', 'sort_order' => 5],
        ];

        foreach ($faqs as $faq) {
            Faq::query()->updateOrCreate(['question' => $faq['question']], $faq);
        }
    }

    private function gallery(): void
    {
        $heroes = [
            ['path' => 'images/image12.jpg', 'alt' => 'GPC community', 'type' => 'hero', 'sort_order' => 1],
            ['path' => 'images/image19.jpg', 'alt' => 'GPC event', 'type' => 'hero', 'sort_order' => 2],
        ];

        $gallery = [
            ['path' => 'images/image19.jpg', 'alt' => 'GPC event', 'type' => 'gallery', 'sort_order' => 1],
            ['path' => 'images/image13.jpg', 'alt' => 'Workshop', 'type' => 'gallery', 'sort_order' => 2],
            ['path' => 'images/image16.jpg', 'alt' => 'Community', 'type' => 'gallery', 'sort_order' => 3],
            ['path' => 'images/image15.jpg', 'alt' => 'Empowerment', 'type' => 'gallery', 'sort_order' => 4],
        ];

        foreach (array_merge($heroes, $gallery) as $image) {
            GalleryImage::query()->updateOrCreate(
                ['path' => $image['path'], 'type' => $image['type']],
                $image
            );
        }
    }

    private function news(): void
    {
        if (News::query()->exists()) {
            return;
        }

        $item = News::query()->create([
            'title' => 'GPC Launches New Skills Program',
            'content' => 'Empowering young women with vocational training in Lilongwe district. Girl Power for Change continues to expand opportunities for adolescent girls and young women through skills development, mentorship, and community partnerships.',
            'published_at' => now()->toDateString(),
        ]);

        $item->images()->create([
            'path' => 'images/image12.jpg',
            'is_cover' => true,
            'sort_order' => 0,
        ]);
    }
}
