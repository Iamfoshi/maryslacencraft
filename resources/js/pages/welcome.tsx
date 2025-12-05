import { Head } from '@inertiajs/react';
import React, { useState, useEffect } from 'react';
import { Phone, MapPin, Clock, ChevronDown, Menu, X, Star, Heart, Sparkles, Scissors, Gift, Calendar } from 'lucide-react';

interface SeoData {
    title?: string;
    description?: string;
    keywords?: string;
    ogTitle?: string;
    ogDescription?: string;
    ogImage?: string;
    ogType?: string;
    ogSiteName?: string;
    twitterCard?: string;
    twitterSite?: string;
    canonicalUrl?: string;
    localBusiness?: Record<string, any>;
    googleAnalyticsId?: string;
    googleTagManagerId?: string;
    facebookPixelId?: string;
    customHeadScripts?: string;
    customBodyScripts?: string;
    googleSiteVerification?: string;
    bingSiteVerification?: string;
    pinterestSiteVerification?: string;
    socialLinks?: {
        facebook?: string;
        instagram?: string;
        twitter?: string;
        pinterest?: string;
        yelp?: string;
        google?: string;
    };
}

interface Props {
    seo?: SeoData;
    hero?: any;
    about?: any;
    categories?: any[];
    gallery?: any[];
    testimonials?: any[];
    settings?: {
        site_name?: string;
        location_name?: string;
        phone?: string;
        address_line1?: string;
        address_line2?: string;
        hours?: {
            monday?: string;
            tuesday?: string;
            wednesday?: string;
            thursday?: string;
            friday?: string;
            saturday?: string;
            sunday?: string;
        };
        footer_tagline?: string;
    };
}

export default function Welcome({ seo, hero, about, categories, gallery, testimonials: dbTestimonials, settings }: Props) {
    const [isScrolled, setIsScrolled] = useState(false);
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
    const [activeTestimonial, setActiveTestimonial] = useState(0);
    const [cookieConsent, setCookieConsent] = useState<'pending' | 'accepted' | 'declined'>('pending');
    const [showCookieBanner, setShowCookieBanner] = useState(false);
    
    // Contact form state
    const [contactForm, setContactForm] = useState({
        name: '',
        email: '',
        phone: '',
        subject: 'General Inquiry',
        message: '',
    });
    const [formStatus, setFormStatus] = useState<'idle' | 'submitting' | 'success' | 'error'>('idle');
    const [formMessage, setFormMessage] = useState('');

    // Check for existing cookie consent on mount
    useEffect(() => {
        const consent = localStorage.getItem('cookie_consent');
        if (consent === 'accepted') {
            setCookieConsent('accepted');
            setShowCookieBanner(false);
        } else if (consent === 'declined') {
            setCookieConsent('declined');
            setShowCookieBanner(false);
        } else {
            // Show banner after a short delay for better UX
            const timer = setTimeout(() => setShowCookieBanner(true), 1500);
            return () => clearTimeout(timer);
        }
    }, []);

    const handleAcceptCookies = () => {
        localStorage.setItem('cookie_consent', 'accepted');
        setCookieConsent('accepted');
        setShowCookieBanner(false);
    };

    const handleDeclineCookies = () => {
        localStorage.setItem('cookie_consent', 'declined');
        setCookieConsent('declined');
        setShowCookieBanner(false);
    };

    // Contact form submission
    const handleContactSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setFormStatus('submitting');
        setFormMessage('');

        try {
            const response = await fetch('/contact', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                },
                body: JSON.stringify(contactForm),
            });

            const data = await response.json();

            if (response.ok && data.success) {
                setFormStatus('success');
                setFormMessage(data.message || 'Thank you! Your message has been sent.');
                setContactForm({ name: '', email: '', phone: '', subject: 'General Inquiry', message: '' });
            } else {
                setFormStatus('error');
                if (data.errors) {
                    const errorMessages = Object.values(data.errors).flat().join(' ');
                    setFormMessage(errorMessages);
                } else {
                    setFormMessage(data.message || 'Something went wrong. Please try again.');
                }
            }
        } catch (error) {
            setFormStatus('error');
            setFormMessage('Network error. Please check your connection and try again.');
        }
    };

    // Default data for fallback
    const defaultProducts = [
        { icon: <Heart className="w-8 h-8" />, title: 'Ribbons', description: 'Satin, grosgrain, organza, velvet and more in every color imaginable', color: 'from-pink-100 to-pink-50', image: null },
        { icon: <Sparkles className="w-8 h-8" />, title: 'Laces & Trims', description: 'Vintage-inspired and contemporary laces for elegant finishing touches', color: 'from-amber-100 to-amber-50', image: null },
        { icon: <Gift className="w-8 h-8" />, title: 'Party Favors', description: 'Beautiful favors and supplies for weddings, quinceañeras, baby showers & all events', color: 'from-rose-100 to-rose-50', image: null },
        { icon: <Star className="w-8 h-8" />, title: 'Flowers', description: 'Silk flowers, floral arrangements and supplies for stunning decorations', color: 'from-pink-100 to-pink-50', image: null },
        { icon: <Scissors className="w-8 h-8" />, title: 'Baskets', description: 'Decorative baskets in all sizes perfect for gifts and arrangements', color: 'from-amber-100 to-amber-50', image: null },
        { icon: <Calendar className="w-8 h-8" />, title: 'Event Supplies', description: 'Everything you need for birthdays, holidays, and special celebrations', color: 'from-stone-200 to-stone-100', image: null },
    ];

    const defaultTestimonials = [
        { text: "Mary's selection is unmatched! I've been a customer for 5 years and always find the perfect ribbons for my wedding invitations business.", author: 'Sarah Mitchell', title: 'Wedding Stationery Designer' },
        { text: "The quality of laces here is exceptional. I travel 2 hours just to visit this store because nothing else compares.", author: 'Emily Chen', title: 'Fashion Designer' },
        { text: "Best craft store in the area! Mary always helps me find exactly what I need, even when I don't know what I'm looking for.", author: 'Jennifer Adams', title: 'DIY Enthusiast' },
    ];

    const defaultGalleryItems = [
        { title: 'Wedding Decor', category: 'Weddings', image: null, hue: 'bg-gradient-to-br from-pink-200 via-rose-100 to-white', isLarge: true },
        { title: 'Quinceañera', category: 'Events', image: null, hue: 'bg-gradient-to-br from-fuchsia-200 via-pink-100 to-white', isLarge: false },
        { title: 'Baby Showers', category: 'Baby Shower', image: null, hue: 'bg-gradient-to-br from-sky-200 via-blue-100 to-white', isLarge: false },
        { title: 'Birthday Parties', category: 'Events', image: null, hue: 'bg-gradient-to-br from-amber-200 via-orange-100 to-white', isLarge: false },
        { title: 'Floral Arrangements', category: 'Flowers', image: null, hue: 'bg-gradient-to-br from-rose-300 via-pink-200 to-white', isLarge: true },
        { title: 'Gift Baskets', category: 'Gifts', image: null, hue: 'bg-gradient-to-br from-stone-200 via-stone-100 to-white', isLarge: false },
    ];

    // Use database data or fallback to defaults
    const products = categories && categories.length > 0 
        ? categories.map(cat => ({
            icon: getIconForCategory(cat.icon || cat.title),
            title: cat.title,
            description: cat.description,
            color: `from-${cat.color_from} to-${cat.color_to}`,
            image: cat.image ? `/storage/${cat.image}` : null,
        }))
        : defaultProducts;

    const testimonials = dbTestimonials && dbTestimonials.length > 0
        ? dbTestimonials.map(t => ({
            text: t.content,
            author: t.author_name,
            title: t.author_title,
        }))
        : defaultTestimonials;

    const galleryItems = gallery && gallery.length > 0
        ? gallery.map(g => ({
            title: g.title,
            category: g.category,
            image: g.image ? `/storage/${g.image}` : null,
            hue: `bg-gradient-to-br from-${g.gradient_from || 'pink-200'} via-${g.gradient_via || 'rose-100'} to-${g.gradient_to || 'white'}`,
            isLarge: g.is_large || g.is_featured,
        }))
        : defaultGalleryItems;

    // Helper function to get icon component
    function getIconForCategory(iconOrTitle: string) {
        const iconMap: Record<string, React.ReactNode> = {
            '🎀': <Heart className="w-8 h-8" />,
            '✨': <Sparkles className="w-8 h-8" />,
            '🎁': <Gift className="w-8 h-8" />,
            '🌸': <Star className="w-8 h-8" />,
            '🧺': <Scissors className="w-8 h-8" />,
            '🎉': <Calendar className="w-8 h-8" />,
            'Ribbons': <Heart className="w-8 h-8" />,
            'Laces': <Sparkles className="w-8 h-8" />,
            'Party': <Gift className="w-8 h-8" />,
            'Flowers': <Star className="w-8 h-8" />,
            'Baskets': <Scissors className="w-8 h-8" />,
            'Event': <Calendar className="w-8 h-8" />,
        };
        
        for (const [key, icon] of Object.entries(iconMap)) {
            if (iconOrTitle?.includes(key)) return icon;
        }
        return <Star className="w-8 h-8" />;
    }

    useEffect(() => {
        const handleScroll = () => {
            setIsScrolled(window.scrollY > 50);
        };
        window.addEventListener('scroll', handleScroll);
        return () => window.removeEventListener('scroll', handleScroll);
    }, []);

    useEffect(() => {
        const interval = setInterval(() => {
            setActiveTestimonial((prev) => (prev + 1) % testimonials.length);
        }, 5000);
        return () => clearInterval(interval);
    }, [testimonials.length]);

    const scrollToSection = (id: string) => {
        const element = document.getElementById(id);
        if (element) {
            element.scrollIntoView({ behavior: 'smooth' });
            setMobileMenuOpen(false);
        }
    };

    const navLinks = [
        { name: 'Home', id: 'home' },
        { name: 'About', id: 'about' },
        { name: 'Products', id: 'products' },
        { name: 'Gallery', id: 'gallery' },
        { name: 'Contact', id: 'contact' },
    ];

    // SEO defaults
    const pageTitle = seo?.title || "Mary's Lace n Craft | Wholesale & Retail Craft Supplies";
    const pageDescription = seo?.description || "Your one-stop shop for wholesale and retail craft supplies in La Puente, CA.";

    // Site settings with defaults
    const siteName = settings?.site_name || "Mary's Lace n Craft";
    const locationName = settings?.location_name || "South Hill Square";
    const phone = settings?.phone || "(626) 918-8511";
    const addressLine1 = settings?.address_line1 || "South Hills Shopping Center";
    const addressLine2 = settings?.address_line2 || "1629 N Hacienda Blvd, La Puente, CA 91744";
    const hours = settings?.hours || {
        monday: '9 AM – 7 PM',
        tuesday: '9 AM – 7 PM',
        wednesday: '9 AM – 7 PM',
        thursday: '9 AM – 7 PM',
        friday: '9 AM – 7 PM',
        saturday: '10 AM – 6 PM',
        sunday: '12 PM – 5 PM',
    };
    
    // Get today's hours
    const days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'] as const;
    const today = days[new Date().getDay()];
    const todayHours = hours[today] || 'Closed';

    return (
        <>
            <Head title={pageTitle}>
                {/* Primary Meta Tags */}
                <meta name="description" content={pageDescription} />
                {seo?.keywords && <meta name="keywords" content={seo.keywords} />}
                {seo?.canonicalUrl && <link rel="canonical" href={seo.canonicalUrl} />}
                
                {/* Search Engine Verification */}
                {seo?.googleSiteVerification && <meta name="google-site-verification" content={seo.googleSiteVerification} />}
                {seo?.bingSiteVerification && <meta name="msvalidate.01" content={seo.bingSiteVerification} />}
                {seo?.pinterestSiteVerification && <meta name="p:domain_verify" content={seo.pinterestSiteVerification} />}
                
                {/* Open Graph / Facebook */}
                <meta property="og:type" content={seo?.ogType || 'website'} />
                <meta property="og:title" content={seo?.ogTitle || pageTitle} />
                <meta property="og:description" content={seo?.ogDescription || pageDescription} />
                {seo?.ogImage && <meta property="og:image" content={seo.ogImage} />}
                {seo?.ogSiteName && <meta property="og:site_name" content={seo.ogSiteName} />}
                <meta property="og:url" content={seo?.canonicalUrl || window.location.href} />
                
                {/* Twitter */}
                <meta name="twitter:card" content={seo?.twitterCard || 'summary_large_image'} />
                <meta name="twitter:title" content={seo?.ogTitle || pageTitle} />
                <meta name="twitter:description" content={seo?.ogDescription || pageDescription} />
                {seo?.ogImage && <meta name="twitter:image" content={seo.ogImage} />}
                {seo?.twitterSite && <meta name="twitter:site" content={seo.twitterSite} />}
                
                {/* Fonts */}
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link
                    href="https://fonts.bunny.net/css?family=cormorant-garamond:400,500,600,700|nunito-sans:300,400,500,600"
                    rel="stylesheet"
                />
                
                {/* Local Business Structured Data */}
                {seo?.localBusiness && (
                    <script type="application/ld+json">
                        {JSON.stringify(seo.localBusiness)}
                    </script>
                )}
                
                {/* Google Analytics - Only load if cookies accepted */}
                {cookieConsent === 'accepted' && seo?.googleAnalyticsId && (
                    <>
                        <script async src={`https://www.googletagmanager.com/gtag/js?id=${seo.googleAnalyticsId}`}></script>
                        <script>
                            {`window.dataLayer = window.dataLayer || [];
                            function gtag(){dataLayer.push(arguments);}
                            gtag('js', new Date());
                            gtag('config', '${seo.googleAnalyticsId}');`}
                        </script>
                    </>
                )}
                
                {/* Google Tag Manager - Only load if cookies accepted */}
                {cookieConsent === 'accepted' && seo?.googleTagManagerId && (
                    <script>
                        {`(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
                        })(window,document,'script','dataLayer','${seo.googleTagManagerId}');`}
                    </script>
                )}
                
                {/* Facebook Pixel - Only load if cookies accepted */}
                {cookieConsent === 'accepted' && seo?.facebookPixelId && (
                    <script>
                        {`!function(f,b,e,v,n,t,s)
                        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
                        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
                        n.queue=[];t=b.createElement(e);t.async=!0;
                        t.src=v;s=b.getElementsByTagName(e)[0];
                        s.parentNode.insertBefore(t,s)}(window, document,'script',
                        'https://connect.facebook.net/en_US/fbevents.js');
                        fbq('init', '${seo.facebookPixelId}');
                        fbq('track', 'PageView');`}
                    </script>
                )}
            </Head>

            <div className="min-h-screen bg-[#FDFBF7] text-[#2D2A26]" style={{ fontFamily: "'Nunito Sans', sans-serif" }}>
                {/* Floating Contact Bar */}
                <div className="fixed top-0 left-0 right-0 z-50 bg-[#2D2A26] text-white py-2 px-4 text-sm">
                    <div className="max-w-7xl mx-auto flex flex-wrap justify-center md:justify-between items-center gap-2 md:gap-4">
                        <div className="flex items-center gap-4 md:gap-6 flex-wrap justify-center">
                            <a href={`tel:${phone.replace(/[^0-9]/g, '')}`} className="flex items-center gap-2 hover:text-[#E8B4B8] transition-colors">
                                <Phone className="w-4 h-4" />
                                <span>{phone}</span>
                            </a>
                        </div>
                        <a href={seo?.socialLinks?.google || `https://maps.google.com/?q=${encodeURIComponent(addressLine2)}`} target="_blank" rel="noopener noreferrer" className="hidden md:flex items-center gap-2 text-[#D4C4B0] hover:text-[#E8B4B8] transition-colors">
                            <MapPin className="w-4 h-4" />
                            <span>{addressLine2}</span>
                        </a>
                        <div className="hidden lg:flex items-center gap-2 text-[#D4C4B0]">
                            <Clock className="w-4 h-4" />
                            <span>Today/Hoy: {todayHours}</span>
                        </div>
                    </div>
                </div>

                {/* Navigation */}
                <nav className={`fixed top-10 left-0 right-0 z-40 transition-all duration-500 ${isScrolled ? 'bg-white/95 backdrop-blur-md shadow-lg' : 'bg-transparent'}`}>
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="flex justify-between items-center h-20">
                            <button onClick={() => scrollToSection('home')} className="flex items-center gap-2 group">
                                <span className="text-3xl">✿</span>
                                <span className="text-xl font-semibold tracking-wide" style={{ fontFamily: "'Cormorant Garamond', serif" }}>
                                    Mary's Lace n Craft
                                </span>
                            </button>

                            {/* Desktop Navigation */}
                            <div className="hidden md:flex items-center gap-8">
                                {navLinks.map((link) => (
                                    <button
                                        key={link.id}
                                        onClick={() => scrollToSection(link.id)}
                                        className="relative text-[#2D2A26] hover:text-[#C4927A] transition-colors duration-300 after:absolute after:bottom-0 after:left-0 after:w-0 after:h-0.5 after:bg-[#E8B4B8] hover:after:w-full after:transition-all after:duration-300"
                                    >
                                        {link.name}
                                    </button>
                                ))}
                            </div>

                            {/* Mobile Menu Button */}
                            <button
                                onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
                                className="md:hidden p-2 hover:bg-[#F5EDE4] rounded-lg transition-colors"
                            >
                                {mobileMenuOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
                            </button>
                        </div>
                    </div>

                    {/* Mobile Menu */}
                    <div className={`md:hidden absolute top-full left-0 right-0 bg-white shadow-lg transition-all duration-300 ${mobileMenuOpen ? 'opacity-100 visible' : 'opacity-0 invisible'}`}>
                        <div className="px-4 py-6 space-y-4">
                            {navLinks.map((link) => (
                                <button
                                    key={link.id}
                                    onClick={() => scrollToSection(link.id)}
                                    className="block w-full text-left py-2 px-4 hover:bg-[#F5EDE4] rounded-lg transition-colors"
                                >
                                    {link.name}
                                </button>
                            ))}
                        </div>
                    </div>
                    </nav>

                {/* Hero Section */}
                <section id="home" className="relative min-h-screen flex flex-col items-center justify-center overflow-hidden pt-32 pb-24">
                    {/* Decorative Background */}
                    <div className="absolute inset-0 overflow-hidden">
                        <div className="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-[#F5EDE4] via-[#FDFBF7] to-[#FFF5F5]" />
                        <div className="absolute top-20 right-10 w-96 h-96 bg-[#E8B4B8]/20 rounded-full blur-3xl animate-pulse" />
                        <div className="absolute bottom-20 left-10 w-80 h-80 bg-[#D4C4B0]/30 rounded-full blur-3xl" />
                        <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] border border-[#E8B4B8]/20 rounded-full" />
                        <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] border border-[#D4C4B0]/20 rounded-full" />
                        
                        {/* Floating Ribbons */}
                        <div className="absolute top-32 right-20 text-6xl opacity-20 animate-bounce" style={{ animationDuration: '3s' }}>🎀</div>
                        <div className="absolute bottom-40 left-20 text-5xl opacity-15 animate-bounce" style={{ animationDuration: '4s', animationDelay: '1s' }}>✂️</div>
                        <div className="absolute top-1/2 right-32 text-4xl opacity-10 animate-bounce" style={{ animationDuration: '5s', animationDelay: '2s' }}>🧵</div>
                    </div>

                    <div className="relative z-10 max-w-5xl mx-auto px-4 text-center">
                        <div className="inline-block mb-6 px-6 py-2 bg-white/80 backdrop-blur-sm rounded-full border border-[#E8B4B8]/30 shadow-sm">
                            <span className="text-[#C4927A] text-sm font-medium tracking-wider uppercase">Wholesale & Retail Craft Supplies</span>
                        </div>
                        
                        <h1 className="text-5xl sm:text-6xl md:text-7xl lg:text-8xl font-light mb-6 leading-tight" style={{ fontFamily: "'Cormorant Garamond', serif" }}>
                            Where Creativity
                                <br />
                            <em className="text-[#C4927A]">Blossoms</em>
                            </h1>
                        
                        <p className="text-lg md:text-xl text-[#5C5752] max-w-2xl mx-auto mb-10 leading-relaxed">
                            Your one-stop shop for wholesale and retail craft supplies — lace, ribbons, baskets, flowers, and party favors for all your special events.
                        </p>
                        
                        <div className="flex flex-col sm:flex-row gap-4 justify-center mb-8">
                            <button
                                onClick={() => scrollToSection('products')}
                                className="px-8 py-4 bg-[#2D2A26] text-white rounded-full hover:bg-[#1a1815] transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-1"
                            >
                                Explore Collection
                            </button>
                            <button
                                onClick={() => scrollToSection('contact')}
                                className="px-8 py-4 bg-white text-[#2D2A26] rounded-full border-2 border-[#2D2A26] hover:bg-[#2D2A26] hover:text-white transition-all duration-300"
                            >
                                Visit Our Store
                            </button>
                        </div>

                        {/* Social Icons */}
                        <div className="flex flex-col items-center gap-4 mt-4">
                            <span className="text-xs text-[#5C5752]/70 tracking-widest uppercase">Follow Us</span>
                            <div className="flex items-center justify-center gap-4">
                            
                            {/* Facebook */}
                            <a 
                                href={seo?.socialLinks?.facebook || "https://facebook.com"} 
                                            target="_blank"
                                rel="noopener noreferrer"
                                className="group w-12 h-12 rounded-full bg-white/90 backdrop-blur-sm border border-[#D4C4B0]/40 flex items-center justify-center text-[#5C5752] hover:bg-[#1877F2] hover:text-white hover:border-[#1877F2] hover:scale-110 hover:-translate-y-1 transition-all duration-300 shadow-md hover:shadow-lg"
                                aria-label="Facebook"
                            >
                                <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                                            </svg>
                                        </a>

                            {/* Instagram */}
                            <a 
                                href={seo?.socialLinks?.instagram || "https://instagram.com"} 
                                            target="_blank"
                                rel="noopener noreferrer"
                                className="group w-12 h-12 rounded-full bg-white/90 backdrop-blur-sm border border-[#D4C4B0]/40 flex items-center justify-center text-[#5C5752] hover:bg-gradient-to-br hover:from-[#833AB4] hover:via-[#FD1D1D] hover:to-[#F77737] hover:text-white hover:border-transparent hover:scale-110 hover:-translate-y-1 transition-all duration-300 shadow-md hover:shadow-lg"
                                aria-label="Instagram"
                            >
                                <svg className="w-5 h-5" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                                    <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                                            </svg>
                                        </a>

                            {/* Twitter/X */}
                            <a 
                                href={seo?.socialLinks?.twitter || "https://x.com"} 
                                            target="_blank"
                                rel="noopener noreferrer"
                                className="group w-12 h-12 rounded-full bg-white/90 backdrop-blur-sm border border-[#D4C4B0]/40 flex items-center justify-center text-[#5C5752] hover:bg-[#000000] hover:text-white hover:border-[#000000] hover:scale-110 hover:-translate-y-1 transition-all duration-300 shadow-md hover:shadow-lg"
                                aria-label="Twitter/X"
                            >
                                <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                </svg>
                            </a>

                            {/* Yelp */}
                            <a 
                                href={seo?.socialLinks?.yelp || "https://yelp.com"} 
                                target="_blank" 
                                rel="noopener noreferrer"
                                className="group w-12 h-12 rounded-full bg-white/90 backdrop-blur-sm border border-[#D4C4B0]/40 flex items-center justify-center text-[#5C5752] hover:bg-[#D32323] hover:text-white hover:border-[#D32323] hover:scale-110 hover:-translate-y-1 transition-all duration-300 shadow-md hover:shadow-lg"
                                aria-label="Yelp"
                            >
                                <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.16 12.594l-4.995 1.433c-.96.276-1.74-.8-1.176-1.63l2.905-4.308a1.072 1.072 0 0 1 1.596-.206 9.194 9.194 0 0 1 2.364 3.252 1.073 1.073 0 0 1-.694 1.459zm-3.008 5.651a9.094 9.094 0 0 1-3.103 2.469 1.073 1.073 0 0 1-1.488-.645l-1.5-4.973c-.293-.97.8-1.74 1.648-1.163l4.373 2.967c.757.517.71 1.642.07 1.345zm-7.003 2.38a9.094 9.094 0 0 1-3.105-2.47 1.073 1.073 0 0 1 .07-1.344l4.374-2.967c.847-.577 1.94.193 1.647 1.162l-1.5 4.974a1.073 1.073 0 0 1-1.486.645zM3.84 12.594a1.073 1.073 0 0 1-.694-1.459 9.194 9.194 0 0 1 2.364-3.252 1.072 1.072 0 0 1 1.596.206l2.905 4.308c.564.83-.217 1.906-1.177 1.63l-4.994-1.433zM9.6 7.646L8.073 2.69A1.073 1.073 0 0 1 8.88 1.4a9.194 9.194 0 0 1 6.24 0 1.073 1.073 0 0 1 .807 1.29L14.4 7.646c-.23.963-1.57.963-1.8 0l-1.2-4.957a.36.36 0 0 0-.7 0L9.6 7.646z"/>
                                            </svg>
                                        </a>

                            {/* Google Maps */}
                            <a 
                                href={seo?.socialLinks?.google || "https://maps.google.com/?q=1629+N+Hacienda+Blvd,+La+Puente,+CA+91744"} 
                                        target="_blank"
                                rel="noopener noreferrer"
                                className="group w-12 h-12 rounded-full bg-white/90 backdrop-blur-sm border border-[#D4C4B0]/40 flex items-center justify-center text-[#5C5752] hover:bg-[#4285F4] hover:text-white hover:border-[#4285F4] hover:scale-110 hover:-translate-y-1 transition-all duration-300 shadow-md hover:shadow-lg"
                                aria-label="Google Maps"
                                    >
                                <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                </svg>
                                    </a>
                        </div>
                        </div>
                    </div>

                    {/* Scroll Indicator */}
                    <button
                        onClick={() => scrollToSection('about')}
                        className="mt-8 flex flex-col items-center gap-2 text-[#5C5752]/60 animate-bounce cursor-pointer hover:text-[#C4927A] transition-colors"
                    >
                        <span className="text-sm tracking-wider">Scroll to explore</span>
                        <ChevronDown className="w-5 h-5" />
                    </button>
                </section>

                {/* About Section */}
                <section id="about" className="relative py-24 md:py-32 overflow-hidden">
                    {/* Background Pattern */}
                    <div className="absolute inset-0 bg-gradient-to-b from-[#FDFBF7] via-white to-[#F5EDE4]" />
                    <div className="absolute inset-0 opacity-5" style={{ backgroundImage: 'url("data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23C4927A\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E")' }} />

                    <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                            {/* Image Side */}
                            <div className="relative">
                                <div className="relative aspect-[4/5] rounded-2xl overflow-hidden shadow-2xl">
                                    <div className="absolute inset-0 bg-gradient-to-br from-[#E8B4B8] via-[#D4C4B0] to-[#C4927A]" />
                                    <div className="absolute inset-0 flex items-center justify-center">
                                        <span className="text-9xl">🎀</span>
                                    </div>
                                </div>
                                
                                {/* Floating Stats Card */}
                                <div className="absolute -bottom-6 -right-6 bg-white rounded-2xl shadow-xl p-6 border border-[#E8B4B8]/20">
                                    <div className="text-4xl font-bold text-[#C4927A]" style={{ fontFamily: "'Cormorant Garamond', serif" }}>14+</div>
                                    <div className="text-sm text-[#5C5752]">Years of Passion</div>
                                </div>
                                
                                {/* Decorative Element */}
                                <div className="absolute -top-4 -left-4 w-24 h-24 border-2 border-[#E8B4B8]/30 rounded-full" />
                            </div>

                            {/* Content Side */}
                            <div className="lg:pl-8">
                                <div className="inline-block mb-4 px-4 py-1 bg-[#E8B4B8]/20 rounded-full">
                                    <span className="text-[#C4927A] text-sm font-medium tracking-wider uppercase">Our Story</span>
                                </div>
                                
                                <h2 className="text-4xl md:text-5xl font-light mb-6 leading-tight" style={{ fontFamily: "'Cormorant Garamond', serif" }}>
                                    Crafted with Love,
                                    <br />
                                    <span className="text-[#C4927A]">Curated with Care</span>
                                </h2>
                                
                                <p className="text-lg text-[#5C5752] mb-6 leading-relaxed">
                                    Mary's Lace n Craft is your trusted source for wholesale and retail craft supplies in La Puente, California.
                                </p>
                                
                                <p className="text-[#5C5752] mb-8 leading-relaxed">
                                    We specialize in lace, ribbons, baskets, flowers, and party favors for all your special events — from weddings and quinceañeras to baby showers and birthday parties. Whether you're a professional event planner or a DIY enthusiast, we have everything you need to make your celebrations beautiful.
                                </p>

                                <div className="grid sm:grid-cols-2 gap-6">
                                    <div className="flex items-start gap-4 p-4 bg-white/60 rounded-xl border border-[#E8B4B8]/20">
                                        <div className="w-10 h-10 bg-[#E8B4B8]/20 rounded-full flex items-center justify-center text-[#C4927A]">
                                            ✦
                                        </div>
                                        <div>
                                            <h4 className="font-semibold text-[#2D2A26]">Premium Quality</h4>
                                            <p className="text-sm text-[#5C5752]">Hand-selected materials</p>
                                        </div>
                                    </div>
                                    <div className="flex items-start gap-4 p-4 bg-white/60 rounded-xl border border-[#E8B4B8]/20">
                                        <div className="w-10 h-10 bg-[#D4C4B0]/30 rounded-full flex items-center justify-center text-[#C4927A]">
                                            ✦
                                        </div>
                                        <div>
                                            <h4 className="font-semibold text-[#2D2A26]">Unique Selection</h4>
                                            <p className="text-sm text-[#5C5752]">Rare finds & classics</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {/* Products Section */}
                <section id="products" className="relative py-24 md:py-32 overflow-hidden">
                    {/* Background */}
                    <div className="absolute inset-0 bg-gradient-to-b from-[#F5EDE4] to-[#FDFBF7]" />
                    <div className="absolute top-0 left-0 w-full h-32 bg-gradient-to-b from-[#F5EDE4] to-transparent" />
                    
                    <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="text-center mb-16">
                            <div className="inline-block mb-4 px-4 py-1 bg-white/80 rounded-full border border-[#E8B4B8]/30">
                                <span className="text-[#C4927A] text-sm font-medium tracking-wider uppercase">Our Collection</span>
                            </div>
                            <h2 className="text-4xl md:text-5xl font-light mb-4" style={{ fontFamily: "'Cormorant Garamond', serif" }}>
                                Discover Our Treasures
                            </h2>
                            <p className="text-[#5C5752] max-w-2xl mx-auto">
                                From delicate laces to vibrant ribbons, find everything you need to bring your creative projects to life.
                            </p>
                        </div>

                        <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                            {products.map((product, index) => (
                                <div
                                    key={index}
                                    className="group relative bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 border border-[#E8B4B8]/10 hover:border-[#E8B4B8]/30 hover:-translate-y-2"
                                >
                                    {/* Product Image */}
                                    {product.image ? (
                                        <div className="relative h-48 overflow-hidden">
                                            <img 
                                                src={product.image} 
                                                alt={product.title}
                                                className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                            />
                                            <div className="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
                                        </div>
                                    ) : (
                                        <div className={`relative h-48 bg-gradient-to-br ${product.color} flex items-center justify-center`}>
                                            <div className="w-20 h-20 bg-white/80 rounded-2xl flex items-center justify-center text-[#C4927A] text-3xl">
                                                {product.icon}
                                            </div>
                                        </div>
                                    )}
                                    
                                    {/* Product Info */}
                                    <div className="p-6">
                                        <div className="flex items-start gap-3 mb-3">
                                            <div className="w-10 h-10 flex-shrink-0 bg-[#F5EDE4] rounded-xl flex items-center justify-center text-[#C4927A]">
                                                {product.icon}
                                            </div>
                                            <h3 className="text-xl font-semibold text-[#2D2A26] pt-1" style={{ fontFamily: "'Cormorant Garamond', serif" }}>
                                                {product.title}
                                            </h3>
                                        </div>
                                        
                                        <p className="text-[#5C5752] text-sm leading-relaxed">
                                            {product.description}
                                        </p>
                                    </div>
                                </div>
                            ))}
                        </div>

                        <div className="text-center mt-12">
                            <p className="text-[#5C5752] mb-4">Can't find what you're looking for?</p>
                            <button
                                onClick={() => scrollToSection('contact')}
                                className="px-8 py-3 bg-transparent text-[#2D2A26] rounded-full border-2 border-[#2D2A26] hover:bg-[#2D2A26] hover:text-white transition-all duration-300"
                            >
                                Contact Us for Special Orders
                            </button>
                        </div>
                    </div>
                </section>

                {/* Gallery Section */}
                <section id="gallery" className="relative py-24 md:py-32 overflow-hidden">
                    <div className="absolute inset-0 bg-white" />
                    
                    <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="text-center mb-16">
                            <div className="inline-block mb-4 px-4 py-1 bg-[#E8B4B8]/20 rounded-full">
                                <span className="text-[#C4927A] text-sm font-medium tracking-wider uppercase">Inspiration</span>
                            </div>
                            <h2 className="text-4xl md:text-5xl font-light mb-4" style={{ fontFamily: "'Cormorant Garamond', serif" }}>
                                Customer Creations
                            </h2>
                            <p className="text-[#5C5752] max-w-2xl mx-auto">
                                See what our amazing customers have created with our materials
                            </p>
                        </div>

                        <div className="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-6">
                            {galleryItems.map((item, index) => (
                                <div
                                    key={index}
                                    className={`group relative overflow-hidden rounded-2xl ${item.isLarge || index === 0 || index === 4 ? 'row-span-2 aspect-[3/4]' : 'aspect-square'}`}
                                >
                                    {item.image ? (
                                        <img 
                                            src={item.image} 
                                            alt={item.title}
                                            className="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                        />
                                    ) : (
                                        <div className={`absolute inset-0 ${item.hue} group-hover:scale-110 transition-transform duration-700`} />
                                    )}
                                    <div className="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-colors duration-300" />
                                    <div className="absolute inset-0 flex flex-col items-center justify-center p-4">
                                        <span className="text-white font-medium text-lg md:text-xl tracking-wide opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-center drop-shadow-lg" style={{ fontFamily: "'Cormorant Garamond', serif" }}>
                                            {item.title}
                                        </span>
                                        {item.category && (
                                            <span className="text-white/80 text-sm mt-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-100">
                                                {item.category}
                                            </span>
                                        )}
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>

                {/* Testimonials Section */}
                <section className="relative py-24 md:py-32 overflow-hidden">
                    <div className="absolute inset-0 bg-gradient-to-br from-[#2D2A26] to-[#1a1815]" />
                    <div className="absolute inset-0 opacity-10" style={{ backgroundImage: 'radial-gradient(circle at 2px 2px, #E8B4B8 1px, transparent 0)', backgroundSize: '40px 40px' }} />

                    <div className="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="text-center">
                            <div className="text-6xl md:text-8xl text-[#E8B4B8]/30 mb-6" style={{ fontFamily: "'Cormorant Garamond', serif" }}>"</div>
                            
                            <div className="relative h-48 md:h-40">
                                {testimonials.map((testimonial, index) => (
                                    <div
                                        key={index}
                                        className={`absolute inset-0 transition-all duration-700 ${index === activeTestimonial ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'}`}
                                    >
                                        <p className="text-xl md:text-2xl text-white/90 leading-relaxed mb-8" style={{ fontFamily: "'Cormorant Garamond', serif" }}>
                                            {testimonial.text}
                                        </p>
                                        <div>
                                            <div className="text-[#E8B4B8] font-medium">{testimonial.author}</div>
                                            <div className="text-white/50 text-sm">{testimonial.title}</div>
                                        </div>
                                    </div>
                                ))}
                            </div>

                            <div className="flex justify-center gap-3 mt-8">
                                {testimonials.map((_, index) => (
                                    <button
                                        key={index}
                                        onClick={() => setActiveTestimonial(index)}
                                        className={`w-2 h-2 rounded-full transition-all duration-300 ${index === activeTestimonial ? 'w-8 bg-[#E8B4B8]' : 'bg-white/30 hover:bg-white/50'}`}
                                    />
                                ))}
                            </div>
                        </div>
                    </div>
                </section>

                {/* Contact Section */}
                <section id="contact" className="relative py-24 md:py-32 overflow-hidden">
                    <div className="absolute inset-0 bg-gradient-to-b from-[#FDFBF7] to-[#F5EDE4]" />
                    <div className="absolute top-0 right-0 w-96 h-96 bg-[#E8B4B8]/10 rounded-full blur-3xl" />
                    <div className="absolute bottom-0 left-0 w-80 h-80 bg-[#D4C4B0]/20 rounded-full blur-3xl" />

                    <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="grid lg:grid-cols-2 gap-12 lg:gap-20">
                            {/* Contact Info */}
                            <div>
                                <div className="inline-block mb-4 px-4 py-1 bg-[#E8B4B8]/20 rounded-full">
                                    <span className="text-[#C4927A] text-sm font-medium tracking-wider uppercase">Get in Touch</span>
                                </div>
                                
                                <h2 className="text-4xl md:text-5xl font-light mb-6" style={{ fontFamily: "'Cormorant Garamond', serif" }}>
                                    Visit Our Store
                                </h2>
                                
                                <p className="text-[#5C5752] mb-10 leading-relaxed">
                                    We'd love to help you find the perfect materials for your next project. Stop by our store or reach out anytime!
                                </p>

                                <div className="space-y-6">
                                    <a href={seo?.socialLinks?.google || `https://maps.google.com/?q=${encodeURIComponent(addressLine2)}`} target="_blank" rel="noopener noreferrer" className="flex items-start gap-4 p-5 bg-white rounded-xl shadow-sm border border-[#E8B4B8]/10 hover:shadow-md hover:border-[#E8B4B8]/30 transition-all duration-300">
                                        <div className="w-12 h-12 bg-[#E8B4B8]/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <MapPin className="w-5 h-5 text-[#C4927A]" />
                                        </div>
                                        <div>
                                            <h4 className="font-semibold text-[#2D2A26] mb-1">Location</h4>
                                            <p className="text-[#5C5752] text-sm mb-1">{locationName}</p>
                                            <p className="text-[#5C5752]">{addressLine1}<br />{addressLine2}</p>
                                        </div>
                                    </a>

                                    <div className="flex items-start gap-4 p-5 bg-white rounded-xl shadow-sm border border-[#E8B4B8]/10 hover:shadow-md transition-shadow duration-300">
                                        <div className="w-12 h-12 bg-[#D4C4B0]/30 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <Clock className="w-5 h-5 text-[#C4927A]" />
                                        </div>
                                        <div className="flex-1">
                                            <h4 className="font-semibold text-[#2D2A26] mb-2">Store Hours / Horario</h4>
                                            <div className="text-[#5C5752] text-sm space-y-1">
                                                <div className="flex justify-between"><span>Monday/Lunes</span><span>{hours.monday}</span></div>
                                                <div className="flex justify-between"><span>Tuesday/Martes</span><span>{hours.tuesday}</span></div>
                                                <div className="flex justify-between"><span>Wednesday/Miércoles</span><span>{hours.wednesday}</span></div>
                                                <div className="flex justify-between"><span>Thursday/Jueves</span><span>{hours.thursday}</span></div>
                                                <div className="flex justify-between"><span>Friday/Viernes</span><span>{hours.friday}</span></div>
                                                <div className="flex justify-between"><span>Saturday/Sábado</span><span>{hours.saturday}</span></div>
                                                <div className="flex justify-between"><span>Sunday/Domingo</span><span>{hours.sunday}</span></div>
                                            </div>
                                        </div>
                                    </div>

                                    <a href={`tel:${phone.replace(/[^0-9]/g, '')}`} className="flex items-start gap-4 p-5 bg-white rounded-xl shadow-sm border border-[#E8B4B8]/10 hover:shadow-md hover:border-[#E8B4B8]/30 transition-all duration-300">
                                        <div className="w-12 h-12 bg-[#E8B4B8]/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <Phone className="w-5 h-5 text-[#C4927A]" />
                                        </div>
                                        <div>
                                            <h4 className="font-semibold text-[#2D2A26] mb-1">Call Us</h4>
                                            <p className="text-[#C4927A] text-lg font-medium">{phone}</p>
                                            <p className="text-[#5C5752] text-sm">Wholesale & Retail Orders</p>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            {/* Contact Form */}
                            <div className="bg-white rounded-2xl shadow-xl p-8 md:p-10 border border-[#E8B4B8]/10">
                                <h3 className="text-2xl font-light mb-8 text-[#2D2A26]" style={{ fontFamily: "'Cormorant Garamond', serif" }}>
                                    Send Us a Message
                                </h3>
                                
                                {formStatus === 'success' ? (
                                    <div className="text-center py-8">
                                        <div className="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <svg className="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                        <h4 className="text-xl font-semibold text-[#2D2A26] mb-2">Message Sent!</h4>
                                        <p className="text-[#5C5752] mb-6">{formMessage}</p>
                                        <button
                                            onClick={() => setFormStatus('idle')}
                                            className="px-6 py-2 border border-[#C4927A] text-[#C4927A] rounded-lg hover:bg-[#C4927A] hover:text-white transition-colors"
                                        >
                                            Send Another Message
                                        </button>
                                    </div>
                                ) : (
                                    <form onSubmit={handleContactSubmit} className="space-y-5">
                                        {formStatus === 'error' && (
                                            <div className="p-4 bg-red-50 border border-red-200 rounded-xl text-red-600 text-sm">
                                                {formMessage}
                                            </div>
                                        )}
                                        
                                        <div>
                                            <label htmlFor="name" className="block text-sm font-medium text-[#5C5752] mb-2">Your Name / Nombre *</label>
                                            <input
                                                type="text"
                                                id="name"
                                                required
                                                value={contactForm.name}
                                                onChange={(e) => setContactForm({ ...contactForm, name: e.target.value })}
                                                className="w-full px-4 py-3 rounded-xl border border-[#E8B4B8]/30 focus:border-[#C4927A] focus:ring-2 focus:ring-[#E8B4B8]/20 outline-none transition-all duration-300 bg-[#FDFBF7]"
                                                placeholder="Mary Smith"
                                            />
                                        </div>
                                        
                                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label htmlFor="email" className="block text-sm font-medium text-[#5C5752] mb-2">Email *</label>
                                                <input
                                                    type="email"
                                                    id="email"
                                                    required
                                                    value={contactForm.email}
                                                    onChange={(e) => setContactForm({ ...contactForm, email: e.target.value })}
                                                    className="w-full px-4 py-3 rounded-xl border border-[#E8B4B8]/30 focus:border-[#C4927A] focus:ring-2 focus:ring-[#E8B4B8]/20 outline-none transition-all duration-300 bg-[#FDFBF7]"
                                                    placeholder="mary@example.com"
                                                />
                                            </div>
                                            <div>
                                                <label htmlFor="phone" className="block text-sm font-medium text-[#5C5752] mb-2">Phone / Teléfono</label>
                                                <input
                                                    type="tel"
                                                    id="phone"
                                                    value={contactForm.phone}
                                                    onChange={(e) => setContactForm({ ...contactForm, phone: e.target.value })}
                                                    className="w-full px-4 py-3 rounded-xl border border-[#E8B4B8]/30 focus:border-[#C4927A] focus:ring-2 focus:ring-[#E8B4B8]/20 outline-none transition-all duration-300 bg-[#FDFBF7]"
                                                    placeholder="(626) 555-1234"
                                                />
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <label htmlFor="subject" className="block text-sm font-medium text-[#5C5752] mb-2">Subject / Asunto *</label>
                                            <select
                                                id="subject"
                                                required
                                                value={contactForm.subject}
                                                onChange={(e) => setContactForm({ ...contactForm, subject: e.target.value })}
                                                className="w-full px-4 py-3 rounded-xl border border-[#E8B4B8]/30 focus:border-[#C4927A] focus:ring-2 focus:ring-[#E8B4B8]/20 outline-none transition-all duration-300 bg-[#FDFBF7]"
                                            >
                                                <option value="General Inquiry">General Inquiry / Consulta General</option>
                                                <option value="Product Availability">Product Availability / Disponibilidad</option>
                                                <option value="Custom Order Request">Custom Order / Pedido Especial</option>
                                                <option value="Wholesale Inquiry">Wholesale / Mayoreo</option>
                                            </select>
                                        </div>
                                        
                                        <div>
                                            <label htmlFor="message" className="block text-sm font-medium text-[#5C5752] mb-2">Your Message / Mensaje *</label>
                                            <textarea
                                                id="message"
                                                required
                                                rows={4}
                                                value={contactForm.message}
                                                onChange={(e) => setContactForm({ ...contactForm, message: e.target.value })}
                                                className="w-full px-4 py-3 rounded-xl border border-[#E8B4B8]/30 focus:border-[#C4927A] focus:ring-2 focus:ring-[#E8B4B8]/20 outline-none transition-all duration-300 bg-[#FDFBF7] resize-none"
                                                placeholder="How can we help you? / ¿Cómo podemos ayudarle?"
                                            />
                                        </div>
                                        
                                        <button
                                            type="submit"
                                            disabled={formStatus === 'submitting'}
                                            className="w-full py-4 bg-[#2D2A26] text-white rounded-xl hover:bg-[#1a1815] transition-all duration-300 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                                        >
                                            {formStatus === 'submitting' ? (
                                                <>
                                                    <svg className="animate-spin h-5 w-5" viewBox="0 0 24 24">
                                                        <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" fill="none" />
                                                        <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                                    </svg>
                                                    Sending...
                                                </>
                                            ) : (
                                                'Send Message / Enviar Mensaje'
                                            )}
                                        </button>
                                    </form>
                                )}
                            </div>
                        </div>
                    </div>
                </section>

                {/* Footer */}
                <footer className="relative py-16 overflow-hidden">
                    <div className="absolute inset-0 bg-[#2D2A26]" />
                    
                    <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="grid md:grid-cols-3 gap-12 mb-12">
                            <div>
                                <div className="flex items-center gap-2 mb-4">
                                    <span className="text-2xl">✿</span>
                                    <span className="text-xl text-white" style={{ fontFamily: "'Cormorant Garamond', serif" }}>
                                        Mary's Lace n Craft
                                    </span>
                                </div>
                                <p className="text-white/60 leading-relaxed">
                                    Your wholesale and retail destination for craft supplies in La Puente, CA. Lace, ribbons, baskets, flowers & party favors for all events.
                                </p>
                            </div>
                            
                            <div>
                                <h4 className="text-white font-semibold mb-4">Quick Links</h4>
                                <ul className="space-y-2">
                                    {navLinks.map((link) => (
                                        <li key={link.id}>
                                            <button
                                                onClick={() => scrollToSection(link.id)}
                                                className="text-white/60 hover:text-[#E8B4B8] transition-colors duration-300"
                                            >
                                                {link.name}
                                            </button>
                                        </li>
                                    ))}
                                </ul>
                        </div>
                            
                            <div>
                                <h4 className="text-white font-semibold mb-4">Contact Info</h4>
                                <div className="space-y-2 text-white/60">
                                    <p>1629 N Hacienda Blvd<br />La Puente, CA 91744</p>
                                    <p><a href="tel:+16269188511" className="hover:text-[#E8B4B8] transition-colors">(626) 918-8511</a></p>
                </div>
            </div>
                        </div>
                        
                        <div className="border-t border-white/10 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                            <p className="text-white/40 text-sm">© 2024 Mary's Lace n Craft. All rights reserved.</p>
                            <p className="text-white/40 text-sm">Made with ♥ for craft lovers everywhere</p>
                        </div>
                </div>
                </footer>

                {/* GDPR Cookie Consent Banner */}
                {showCookieBanner && (
                    <div className="fixed bottom-0 left-0 right-0 z-[100] animate-slide-up">
                        <div className="bg-[#2D2A26] border-t border-[#E8B4B8]/20 shadow-2xl">
                            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-6">
                                <div className="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                                    <div className="flex-1">
                                        <div className="flex items-center gap-2 mb-2">
                                            <span className="text-xl">🍪</span>
                                            <h3 className="text-white font-semibold text-lg">Cookie Consent</h3>
                                        </div>
                                        <p className="text-white/70 text-sm leading-relaxed">
                                            We use cookies to enhance your browsing experience, analyze site traffic, and personalize content. 
                                            By clicking "Accept All", you consent to our use of cookies. You can manage your preferences by clicking "Cookie Settings".
                                        </p>
                                    </div>
                                    <div className="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                                        <button
                                            onClick={handleDeclineCookies}
                                            className="px-6 py-2.5 text-white/70 hover:text-white border border-white/20 hover:border-white/40 rounded-full transition-all duration-300 text-sm font-medium"
                                        >
                                            Decline
                                        </button>
                                        <button
                                            onClick={handleAcceptCookies}
                                            className="px-6 py-2.5 bg-[#C4927A] hover:bg-[#b08269] text-white rounded-full transition-all duration-300 text-sm font-medium shadow-lg hover:shadow-xl"
                                        >
                                            Accept All
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                )}
            </div>

            <style>{`
                @keyframes slide-up {
                    from {
                        transform: translateY(100%);
                        opacity: 0;
                    }
                    to {
                        transform: translateY(0);
                        opacity: 1;
                    }
                }
                .animate-slide-up {
                    animation: slide-up 0.5s ease-out forwards;
                }
            `}</style>
        </>
    );
}
