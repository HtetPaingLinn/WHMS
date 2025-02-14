<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // First, truncate the table to avoid unique constraint violations
        Product::truncate();

        // Then insert your products
        Product::insert([
            [
                'brand' => 'Lenovo',
                'category' => 'Laptop',
                'item_name' => 'Lenovo 15.6" IdeaPad Slim 3i Laptop',
                'product_image_url' => null,
                'product_segment' => 'Stay productive while on the go with the Lenovo 15.6" IdeaPad Slim 3i Laptop. Powered by an Intel Core 3 100U processor, 8GB of DDR5 RAM, and a 256GB M.2 SSD, the IdeaPad Slim 3i can handle productivity apps with ease.',
                'product_serial_number' => 'PR0000001',
                'unit_price_mmk' => 1745000
            ],
            [
                'brand' => 'Apple',
                'category' => 'Accessories',
                'item_name' => 'Apple Magic Mouse (USB-C)',
                'product_image_url' => null,
                'product_segment' => 'Wireless and rechargeable, the black Apple Magic Mouse features an optimized foot design that lets it glide smoothly across your desk. The Multi-Touch surface allows you to perform simple gestures, such as swiping between web pages and scrolling through documents. The rechargeable battery will power your Magic Mouse for about a month or more between',
                'product_serial_number' => 'PR0000002',
                'unit_price_mmk' => 495000
            ],
            [
                'brand' => 'Apple',
                'category' => 'Laptop',
                'item_name' => 'Apple 13" MacBook Air',
                'product_image_url' => null,
                'product_segment' => 'The Midnight Apple 13" MacBook Air now features the Apple M3 chip, which has many improvements over its predecessor, the M2. Now built on 3nm process technology, the M3 8-Core Chip is combined with a 10-Core GPU. The next-gen GPU features Dynamic Caching, hardware-accelerated ray tracing, and mesh shading, all of which significantly increase',
                'product_serial_number' => 'PR0000003',
                'unit_price_mmk' => 6245000
            ],
            [
                'brand' => 'Apple',
                'category' => 'Accessories',
                'item_name' => 'Apple AirPods 4th Gen Earbuds',
                'product_image_url' => null,
                'product_segment' => 'Redesigned for optimal comfort and audio performance, the 4th generation AirPods from Apple offer the powerful H2 chip for superior audio quality and intelligent functionality. With active noise cancellation (ANC), upgraded mics for clear calls, and personalized spatial audio with head tracking, AirPods are more immersive than ever.',
                'product_serial_number' => 'PR0000004',
                'unit_price_mmk' => 1200000
            ],
            [
                'brand' => 'ASUS',
                'category' => 'Laptop',
                'item_name' => 'ASUS 16" Vivobook 16X',
                'product_image_url' => null,
                'product_segment' => 'Whether its for creative work or for gaming, the ASUS 16" Vivobook 16X Laptop can deliver the performance you need. Powered by a 13th Gen Intel Core i9 processor and an NVIDIA GeForce RTX 4060 graphics card, the Vivobook 16X can handle multitasking with ease, while the MUX switch puts you in full control of the graphics. Enjoy your content on the 16',
                'product_serial_number' => 'PR0000005',
                'unit_price_mmk' => 5395000
            ],
            [
                'brand' => 'Pebble',
                'category' => 'Accessories',
                'item_name' => 'Creative Pebble USB 2.0 Speakers',
                'product_image_url' => null,
                'product_segment' => 'The black Creative Pebble USB 2.0 Desktop Speakers from Creative Labs are designed to be compact and modern, with an orb shape thats inspired by the pebbles found in Japanese rock gardens. Their compact 4.4" width and 45° drivers deliver an elevated sound stage for personalized listening without sacrificing much desk space. Simply connect the USB-powered',
                'product_serial_number' => 'PR0000006',
                'unit_price_mmk' => 34750
            ],[
                'brand' => 'Samsung',
                'category' => 'Storage Device',
                'item_name' => 'Samsung 2TB T7 Shield Portable SSD',
                'product_image_url' => null,
                'product_segment' => 'Ideal for outdoor use, the Samsung 2TB T7 Shield Portable SSD features a rubber protected exterior and an IP65 rating, providing content creators with both performance and durability. The rugged design and advanced outer elastomer protect it from drops while still being lightweight and portable. Moreover, the T7 Shield offers users quick performance and little',
                'product_serial_number' => 'PR0000007',
                'unit_price_mmk' => 749950
            ],
            [
                'brand' => 'Pearstone',
                'category' => 'Storage Device',
                'item_name' => 'Pearstone USB 3.2 Gen 2 Type-C',
                'product_image_url' => null,
                'product_segment' => 'Enjoy the convenience of connecting your USB Type-C compatible storage drives, cameras, phones, and other devices to USB Type-A laptops with the Pearstone USB 3.2 Gen 2 Type-C Female to USB Type-A Male Adapter. This compact adapter offers a great solution to having to carry around multiple cables.',
                'product_serial_number' => 'PR0000008',
                'unit_price_mmk' => 1099750
            ],
            [
                'brand' => 'Synology',
                'category' => 'Networking Device',
                'item_name' => 'Synology RT6600 Tri-band',
                'product_image_url' => null,
                'product_segment' => 'Support your computer, mobile, and smart devices with fast and reliable wireless network connections using the RT6600 AX6600 Wireless Tri-Band 2.5G / Gigabit Router from Synology. Powered by a 1.8 GHz processor with 1GB of memory and six external antennas',
                'product_serial_number' => 'PR0000009',
                'unit_price_mmk' => 10995000
            ],[
                'brand' => 'CyberPower',
                'category' => 'Power Supply',
                'item_name' => 'CyberPower CP1500PFCLCD PFC',
                'product_image_url' => null,
                'product_segment' => 'The CP1500PFCLCD PFC Sinewave UPS from CyberPower is a mini-tower device with line-interactive topology that provides battery backup via sine wave output and surge protection for desktop computers, workstations, networking devices, and home entertainment systems requiring active PFC power source compatibility. ',
                'product_serial_number' => 'PR0000010',
                'unit_price_mmk' => 10000000
            ],
            [
                'brand' => 'Apple',
                'category' => 'Laptop',
                'item_name' => 'Apple 14" MacBook Pro',
                'product_image_url' => null,
                'product_segment' => '',
                'product_serial_number' => 'PR0000011',
                'unit_price_mmk' => 549950
            ],
            [
                'brand' => 'Lenovo',
                'category' => 'Laptop',
                'item_name' => 'Lenovo 16" Legion 5i',
                'product_image_url' => null,
                'product_segment' => 'Play your favorite PC games with high frame rates and smooth gameplay with the Lenovo 16" Legion 5i Gaming Laptop. Powered with a 14th Gen Intel Core i9 24-Core processor and a dedicated NVIDIA GeForce RTX 4060 graphics card, the Legion 5i can handle PC games with ease. ',
                'product_serial_number' => 'PR0000012',
                'unit_price_mmk' => 1139950
            ],
            [
                'brand' => 'Logitech',
                'category' => 'Accessories',
                'item_name' => 'Logitech G PRO X SUPERLIGHT',
                'product_image_url' => null,
                'product_segment' => 'Designed for the competitive gamer, the G PRO X SUPERLIGHT Wireless Gaming Mouse from Logitech gives you smooth movement and advanced precision. Logitechs proprietary LIGHTSPEED pro-grade wireless technology provides 2.5 GHz wireless connectivity, giving you an enhanced untethered experience. Logitechs HERO 25K optical sensor gives you a 25,400',
                'product_serial_number' => 'PR0000013',
                'unit_price_mmk' => 1349950
            ],
            [
                'brand' => 'Logitech',
                'category' => 'Accessories',
                'item_name' => 'Logitech G G502 Gaming Mouse',
                'product_image_url' => null,
                'product_segment' => 'Immerse yourself in games, movies, and more with the EX2710Q 27" 165 Hz FreeSync IPS Gaming Monitor in metallic gray from BenQ. Built for gaming, this 27" In-Plane Switching (IPS) monitor features a 2560 x 1440 resolution for plenty of detail. A 1 ms (MPRT) response time, 165 Hz refresh rate, and support for AMD FreeSync Premium ensure smooth on-screen',
                'product_serial_number' => 'PR0000014',
                'unit_price_mmk' => 224950
            ],
            [
                'brand' => 'MSI',
                'category' => 'Graphic Card',
                'item_name' => 'MSI GeForce RTX 3050',
                'product_image_url' => null,
                'product_segment' => 'Based on the Ampere architecture and designed to handle the graphical demands of Full HD 1080p gaming, the MSI GeForce RTX 3050 VENTUS 2X 6G OC Graphics Card brings the power of real-time ray tracing and AI to your PC games. The GPU features 6GB of GDDR6 VRAM and a 96-bit memory interface, offering improved performance and power efficiency',
                'product_serial_number' => 'PR0000015',
                'unit_price_mmk' => 949950
            ],
            [
                'brand' => 'ASUS',
                'category' => 'Laptop',
                'item_name' => 'ASUS 16" Vivobook S 16',
                'product_image_url' => null,
                'product_segment' => 'The ASUS 16" Vivobook S 16 OLED Laptop blends performance with a clean, minimalist design, so you can work from home, office, or anywhere in between. The laptop showcases the power of the Intel Core Ultra 9 processor. The immersive 16" OLED display features a 2880 x 1800 resolution, delivering vibrant colors and deep black levels.',
                'product_serial_number' => 'PR0000016',
                'unit_price_mmk' =>6245000
            ],
            [
                'brand' => 'Acer',
                'category' => 'Laptop',
                'item_name' => 'Acer 15.6" Aspire 5 15',
                'product_image_url' => null,
                'product_segment' => 'Streamline your workload with the robust and flexible Acer 15.6" Aspire 5 15 Laptop. Providing you with ample power to complete your daily tasks or handle your business needs, the Aspire 5 can fit into many different environments with ease.',
                'product_serial_number' => 'PR0000017',
                'unit_price_mmk' => 1850000
            ],
            [
                'brand' => 'ASUS',
                'category' => 'Monitor',
                'item_name' => 'Dell 24" P2425H Monitor',
                'product_image_url' => null,
                'product_segment' => 'The Dell P2425H 24" Monitor offers a vivid picture while promoting eye health. This 24" monitor has a resolution of 1920 x 1080 and a refresh rate of 100 Hz. HDMI, DisplayPort, VGA, and USB inputs allow for a wide selection of connections. 4-star TUV certified for eye comfort, this 250 nits monitor covers 99% of the sRGB color gamut with 16.7 million colors',
                'product_serial_number' => 'PR0000018',
                'unit_price_mmk' =>850000
            ],[
                'brand' => 'Canon',
                'category' => 'Printer',
                'item_name' => 'Canon PIXMA PRO-200S',
                'product_image_url' => null,
                'product_segment' => 'Bring your photos and designs to life with the PIXMA PRO-200S 13" Wireless Inkjet Photo Printer from Canon. Featuring an eight-color dye-based ink system, this 13" printer is designed to meet the demands of photographers and graphic artists alike. It offers beautiful and vibrant print quality, high-speed printing, and outstanding productivity in a compact',
                'product_serial_number' => 'PR0000019',
                'unit_price_mmk' => 3000000
            ],
        ]);
    }
}
