import { type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';
import { 
    Users, 
    FileText, 
    Calculator, 
    Megaphone, 
    Calendar,
    Shield,
    TrendingUp,
    CheckCircle,
    Clock,
    MapPin
} from 'lucide-react';

export default function Welcome() {
    const { auth } = usePage<SharedData>().props;

    return (
        <>
            <Head title="RT/RW Management System">
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
            </Head>
            <div className="flex min-h-screen flex-col items-center bg-gradient-to-br from-blue-50 to-indigo-100 p-6 text-gray-900 lg:justify-center lg:p-8 dark:from-gray-900 dark:to-indigo-950 dark:text-gray-100">
                <header className="mb-8 w-full max-w-6xl">
                    <nav className="flex items-center justify-between">
                        <div className="flex items-center space-x-2">
                            <div className="bg-blue-600 p-2 rounded-lg">
                                <MapPin className="w-6 h-6 text-white" />
                            </div>
                            <span className="text-xl font-bold text-blue-600 dark:text-blue-400">RT/RW Manager</span>
                        </div>
                        <div className="flex items-center gap-4">
                            {auth.user ? (
                                <Link
                                    href={route('dashboard')}
                                    className="inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium"
                                >
                                    Go to Dashboard
                                </Link>
                            ) : (
                                <>
                                    <Link
                                        href={route('login')}
                                        className="inline-block px-4 py-2 text-gray-600 hover:text-blue-600 transition-colors duration-200 dark:text-gray-300 dark:hover:text-blue-400"
                                    >
                                        Log in
                                    </Link>
                                    <Link
                                        href={route('register')}
                                        className="inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium"
                                    >
                                        Register
                                    </Link>
                                </>
                            )}
                        </div>
                    </nav>
                </header>

                <main className="flex w-full max-w-6xl flex-col items-center text-center space-y-12">
                    {/* Hero Section */}
                    <div className="space-y-6">
                        <h1 className="text-4xl md:text-6xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                            🏘️ RT/RW Management System
                        </h1>
                        <p className="text-xl md:text-2xl text-gray-600 max-w-3xl dark:text-gray-300">
                            Streamline your neighborhood community operations with our comprehensive digital management platform
                        </p>
                        <div className="flex flex-wrap justify-center gap-4 mt-8">
                            <div className="bg-white rounded-lg px-4 py-2 shadow-sm border dark:bg-gray-800 dark:border-gray-700">
                                <span className="text-sm text-gray-600 dark:text-gray-400">🏠 Household Management</span>
                            </div>
                            <div className="bg-white rounded-lg px-4 py-2 shadow-sm border dark:bg-gray-800 dark:border-gray-700">
                                <span className="text-sm text-gray-600 dark:text-gray-400">💰 Financial Tracking</span>
                            </div>
                            <div className="bg-white rounded-lg px-4 py-2 shadow-sm border dark:bg-gray-800 dark:border-gray-700">
                                <span className="text-sm text-gray-600 dark:text-gray-400">📋 Document Processing</span>
                            </div>
                        </div>
                    </div>

                    {/* Features Grid */}
                    <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8 w-full">
                        <div className="bg-white rounded-xl p-6 shadow-lg border hover:shadow-xl transition-shadow duration-300 dark:bg-gray-800 dark:border-gray-700">
                            <div className="bg-blue-100 w-12 h-12 rounded-lg flex items-center justify-center mb-4 dark:bg-blue-900">
                                <Users className="w-6 h-6 text-blue-600 dark:text-blue-400" />
                            </div>
                            <h3 className="text-lg font-semibold mb-2">👥 Resident & Household Data</h3>
                            <p className="text-gray-600 text-sm leading-relaxed dark:text-gray-400">
                                Comprehensive database of residents and households with detailed profiles, family relationships, and contact information.
                            </p>
                        </div>

                        <div className="bg-white rounded-xl p-6 shadow-lg border hover:shadow-xl transition-shadow duration-300 dark:bg-gray-800 dark:border-gray-700">
                            <div className="bg-green-100 w-12 h-12 rounded-lg flex items-center justify-center mb-4 dark:bg-green-900">
                                <FileText className="w-6 h-6 text-green-600 dark:text-green-400" />
                            </div>
                            <h3 className="text-lg font-semibold mb-2">📄 Administrative Letters</h3>
                            <p className="text-gray-600 text-sm leading-relaxed dark:text-gray-400">
                                Digital workflow for processing administrative letters, permits, certificates with approval tracking and document management.
                            </p>
                        </div>

                        <div className="bg-white rounded-xl p-6 shadow-lg border hover:shadow-xl transition-shadow duration-300 dark:bg-gray-800 dark:border-gray-700">
                            <div className="bg-purple-100 w-12 h-12 rounded-lg flex items-center justify-center mb-4 dark:bg-purple-900">
                                <Calculator className="w-6 h-6 text-purple-600 dark:text-purple-400" />
                            </div>
                            <h3 className="text-lg font-semibold mb-2">💰 Financial Management</h3>
                            <p className="text-gray-600 text-sm leading-relaxed dark:text-gray-400">
                                Track community contributions, monthly fees, expenses, and maintain transparent financial records with detailed reporting.
                            </p>
                        </div>

                        <div className="bg-white rounded-xl p-6 shadow-lg border hover:shadow-xl transition-shadow duration-300 dark:bg-gray-800 dark:border-gray-700">
                            <div className="bg-orange-100 w-12 h-12 rounded-lg flex items-center justify-center mb-4 dark:bg-orange-900">
                                <Megaphone className="w-6 h-6 text-orange-600 dark:text-orange-400" />
                            </div>
                            <h3 className="text-lg font-semibold mb-2">📢 Announcements</h3>
                            <p className="text-gray-600 text-sm leading-relaxed dark:text-gray-400">
                                Broadcast important news, urgent notices, and community updates with priority levels and targeted messaging.
                            </p>
                        </div>

                        <div className="bg-white rounded-xl p-6 shadow-lg border hover:shadow-xl transition-shadow duration-300 dark:bg-gray-800 dark:border-gray-700">
                            <div className="bg-indigo-100 w-12 h-12 rounded-lg flex items-center justify-center mb-4 dark:bg-indigo-900">
                                <Calendar className="w-6 h-6 text-indigo-600 dark:text-indigo-400" />
                            </div>
                            <h3 className="text-lg font-semibold mb-2">📅 Events & Meetings</h3>
                            <p className="text-gray-600 text-sm leading-relaxed dark:text-gray-400">
                                Schedule and manage community events, meetings, gotong royong activities with agenda and participant tracking.
                            </p>
                        </div>

                        <div className="bg-white rounded-xl p-6 shadow-lg border hover:shadow-xl transition-shadow duration-300 dark:bg-gray-800 dark:border-gray-700">
                            <div className="bg-red-100 w-12 h-12 rounded-lg flex items-center justify-center mb-4 dark:bg-red-900">
                                <Shield className="w-6 h-6 text-red-600 dark:text-red-400" />
                            </div>
                            <h3 className="text-lg font-semibold mb-2">🔐 Role-Based Access</h3>
                            <p className="text-gray-600 text-sm leading-relaxed dark:text-gray-400">
                                Secure access control with different roles: System Admin, RT/RW Head, Management, and Residents with appropriate permissions.
                            </p>
                        </div>
                    </div>

                    {/* Stats Section */}
                    <div className="bg-white rounded-xl p-8 shadow-lg border w-full dark:bg-gray-800 dark:border-gray-700">
                        <h2 className="text-2xl font-bold mb-6">✨ Why Choose Our RT/RW Management System?</h2>
                        <div className="grid md:grid-cols-3 gap-8">
                            <div className="text-center">
                                <div className="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 dark:bg-green-900">
                                    <TrendingUp className="w-8 h-8 text-green-600 dark:text-green-400" />
                                </div>
                                <h3 className="text-lg font-semibold mb-2">📈 Efficiency Boost</h3>
                                <p className="text-gray-600 text-sm dark:text-gray-400">
                                    Reduce administrative workload by 70% with automated workflows and digital processes.
                                </p>
                            </div>
                            
                            <div className="text-center">
                                <div className="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 dark:bg-blue-900">
                                    <CheckCircle className="w-8 h-8 text-blue-600 dark:text-blue-400" />
                                </div>
                                <h3 className="text-lg font-semibold mb-2">✅ Transparency</h3>
                                <p className="text-gray-600 text-sm dark:text-gray-400">
                                    Complete transparency in financial records and administrative processes for community trust.
                                </p>
                            </div>
                            
                            <div className="text-center">
                                <div className="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 dark:bg-purple-900">
                                    <Clock className="w-8 h-8 text-purple-600 dark:text-purple-400" />
                                </div>
                                <h3 className="text-lg font-semibold mb-2">⏱️ Time Saving</h3>
                                <p className="text-gray-600 text-sm dark:text-gray-400">
                                    Quick document processing and instant community communication save valuable time.
                                </p>
                            </div>
                        </div>
                    </div>

                    {/* CTA Section */}
                    {!auth.user && (
                        <div className="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl p-8 text-white w-full text-center">
                            <h2 className="text-2xl font-bold mb-4">🚀 Ready to Modernize Your Community Management?</h2>
                            <p className="text-blue-100 mb-6 max-w-2xl mx-auto">
                                Join thousands of RT/RW communities that have already digitized their operations. Start managing your neighborhood more efficiently today!
                            </p>
                            <div className="flex flex-col sm:flex-row gap-4 justify-center items-center">
                                <Link
                                    href={route('register')}
                                    className="inline-flex items-center px-8 py-3 bg-white text-blue-600 rounded-lg hover:bg-gray-100 transition-colors duration-200 font-semibold text-lg"
                                >
                                    Get Started Free 🎉
                                </Link>
                                <Link
                                    href={route('login')}
                                    className="inline-flex items-center px-8 py-3 border-2 border-white text-white rounded-lg hover:bg-white hover:text-blue-600 transition-colors duration-200 font-semibold"
                                >
                                    Sign In
                                </Link>
                            </div>
                        </div>
                    )}

                    <footer className="text-center text-gray-500 text-sm dark:text-gray-400">
                        <p>🏘️ Empowering Indonesian communities with modern digital solutions</p>
                        <p className="mt-2">
                            Built with ❤️ for RT/RW communities across Indonesia
                        </p>
                    </footer>
                </main>
            </div>
        </>
    );
}