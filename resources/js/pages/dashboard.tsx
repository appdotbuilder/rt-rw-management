import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { 
    Users, 
    Home, 
    FileText, 
    Calendar,
    TrendingUp,
    TrendingDown,
    DollarSign,
    Clock,
    Megaphone
} from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

interface DashboardStats {
    households: number;
    residents: number;
    pending_letters: number;
    upcoming_events: number;
}

interface FinancialSummary {
    monthly_income: number;
    monthly_expense: number;
    monthly_balance: number;
}

interface Announcement {
    id: number;
    title: string;
    content: string;
    priority: string;
    created_at: string;
}

interface Event {
    id: number;
    title: string;
    location: string;
    start_datetime: string;
    status: string;
}

interface FinancialRecord {
    id: number;
    type: string;
    category: string;
    description: string;
    amount: number;
    transaction_date: string;
    household?: {
        head_name: string;
    };
}

interface Props {
    stats: DashboardStats;
    recent_announcements: Announcement[];
    upcoming_events: Event[];
    recent_financials: FinancialRecord[];
    financial_summary: FinancialSummary;
    user_role: string;
    user_rt_rw: string | null;
    [key: string]: unknown;
}

export default function Dashboard({ 
    stats, 
    recent_announcements, 
    upcoming_events, 
    recent_financials, 
    financial_summary,
    user_role,
    user_rt_rw 
}: Props) {
    const formatCurrency = (amount: number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
        }).format(amount);
    };

    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString('id-ID', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
        });
    };

    const getRoleBadgeColor = (role: string) => {
        const colors = {
            system_admin: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            rt_head: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            rw_head: 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
            management: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            resident: 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
        };
        return colors[role as keyof typeof colors] || colors.resident;
    };

    const getRoleDisplayName = (role: string) => {
        const names = {
            system_admin: 'System Administrator',
            rt_head: 'RT Head',
            rw_head: 'RW Head',
            management: 'Management',
            resident: 'Resident',
        };
        return names[role as keyof typeof names] || 'Resident';
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            
            <div className="flex h-full flex-1 flex-col gap-6 p-6">
                {/* Welcome Section */}
                <div className="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg p-6 text-white">
                    <div className="flex items-center justify-between">
                        <div>
                            <h1 className="text-2xl font-bold mb-2">🏘️ RT/RW Management Dashboard</h1>
                            <p className="text-blue-100">Welcome back! Here's what's happening in your community today.</p>
                            {user_rt_rw && (
                                <p className="text-blue-200 text-sm mt-1">Managing: {user_rt_rw}</p>
                            )}
                        </div>
                        <div className="text-right">
                            <span className={`inline-flex items-center px-3 py-1 rounded-full text-xs font-medium ${getRoleBadgeColor(user_role)}`}>
                                {getRoleDisplayName(user_role)}
                            </span>
                        </div>
                    </div>
                </div>

                {/* Stats Cards */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div className="bg-white rounded-lg p-6 shadow-sm border dark:bg-gray-800 dark:border-gray-700">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Total Households</p>
                                <p className="text-2xl font-bold text-gray-900 dark:text-gray-100">{stats.households}</p>
                            </div>
                            <div className="bg-blue-100 p-3 rounded-lg dark:bg-blue-900">
                                <Home className="w-6 h-6 text-blue-600 dark:text-blue-400" />
                            </div>
                        </div>
                    </div>

                    <div className="bg-white rounded-lg p-6 shadow-sm border dark:bg-gray-800 dark:border-gray-700">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Total Residents</p>
                                <p className="text-2xl font-bold text-gray-900 dark:text-gray-100">{stats.residents}</p>
                            </div>
                            <div className="bg-green-100 p-3 rounded-lg dark:bg-green-900">
                                <Users className="w-6 h-6 text-green-600 dark:text-green-400" />
                            </div>
                        </div>
                    </div>

                    <div className="bg-white rounded-lg p-6 shadow-sm border dark:bg-gray-800 dark:border-gray-700">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Pending Letters</p>
                                <p className="text-2xl font-bold text-gray-900 dark:text-gray-100">{stats.pending_letters}</p>
                            </div>
                            <div className="bg-orange-100 p-3 rounded-lg dark:bg-orange-900">
                                <FileText className="w-6 h-6 text-orange-600 dark:text-orange-400" />
                            </div>
                        </div>
                    </div>

                    <div className="bg-white rounded-lg p-6 shadow-sm border dark:bg-gray-800 dark:border-gray-700">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Upcoming Events</p>
                                <p className="text-2xl font-bold text-gray-900 dark:text-gray-100">{stats.upcoming_events}</p>
                            </div>
                            <div className="bg-purple-100 p-3 rounded-lg dark:bg-purple-900">
                                <Calendar className="w-6 h-6 text-purple-600 dark:text-purple-400" />
                            </div>
                        </div>
                    </div>
                </div>

                {/* Financial Summary */}
                <div className="bg-white rounded-lg p-6 shadow-sm border dark:bg-gray-800 dark:border-gray-700">
                    <h2 className="text-lg font-semibold mb-4 flex items-center gap-2">
                        <DollarSign className="w-5 h-5" />
                        💰 Monthly Financial Summary
                    </h2>
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div className="text-center p-4 bg-green-50 rounded-lg dark:bg-green-900/20">
                            <div className="flex items-center justify-center mb-2">
                                <TrendingUp className="w-5 h-5 text-green-600 dark:text-green-400 mr-1" />
                                <span className="text-sm font-medium text-green-700 dark:text-green-400">Income</span>
                            </div>
                            <p className="text-xl font-bold text-green-600 dark:text-green-400">
                                {formatCurrency(financial_summary.monthly_income)}
                            </p>
                        </div>
                        <div className="text-center p-4 bg-red-50 rounded-lg dark:bg-red-900/20">
                            <div className="flex items-center justify-center mb-2">
                                <TrendingDown className="w-5 h-5 text-red-600 dark:text-red-400 mr-1" />
                                <span className="text-sm font-medium text-red-700 dark:text-red-400">Expenses</span>
                            </div>
                            <p className="text-xl font-bold text-red-600 dark:text-red-400">
                                {formatCurrency(financial_summary.monthly_expense)}
                            </p>
                        </div>
                        <div className="text-center p-4 bg-blue-50 rounded-lg dark:bg-blue-900/20">
                            <div className="flex items-center justify-center mb-2">
                                <DollarSign className="w-5 h-5 text-blue-600 dark:text-blue-400 mr-1" />
                                <span className="text-sm font-medium text-blue-700 dark:text-blue-400">Balance</span>
                            </div>
                            <p className={`text-xl font-bold ${
                                financial_summary.monthly_balance >= 0 
                                    ? 'text-blue-600 dark:text-blue-400' 
                                    : 'text-red-600 dark:text-red-400'
                            }`}>
                                {formatCurrency(financial_summary.monthly_balance)}
                            </p>
                        </div>
                    </div>
                </div>

                <div className="grid lg:grid-cols-2 gap-6">
                    {/* Recent Announcements */}
                    <div className="bg-white rounded-lg p-6 shadow-sm border dark:bg-gray-800 dark:border-gray-700">
                        <h2 className="text-lg font-semibold mb-4 flex items-center gap-2">
                            <Megaphone className="w-5 h-5" />
                            📢 Recent Announcements
                        </h2>
                        <div className="space-y-3">
                            {recent_announcements.length > 0 ? (
                                recent_announcements.map((announcement) => (
                                    <div key={announcement.id} className="border-l-4 border-blue-500 pl-4 py-2">
                                        <div className="flex items-start justify-between">
                                            <div className="flex-1">
                                                <h3 className="font-medium text-gray-900 dark:text-gray-100 line-clamp-1">
                                                    {announcement.title}
                                                </h3>
                                                <p className="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mt-1">
                                                    {announcement.content}
                                                </p>
                                            </div>
                                            <span className={`px-2 py-1 text-xs rounded-full ml-2 ${
                                                announcement.priority === 'urgent' 
                                                    ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                                    : announcement.priority === 'high'
                                                    ? 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200'
                                                    : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200'
                                            }`}>
                                                {announcement.priority}
                                            </span>
                                        </div>
                                        <p className="text-xs text-gray-500 mt-2 dark:text-gray-500">
                                            {formatDate(announcement.created_at)}
                                        </p>
                                    </div>
                                ))
                            ) : (
                                <div className="text-center py-8 text-gray-500 dark:text-gray-400">
                                    <Megaphone className="w-12 h-12 mx-auto mb-2 opacity-50" />
                                    <p>No recent announcements</p>
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Upcoming Events */}
                    <div className="bg-white rounded-lg p-6 shadow-sm border dark:bg-gray-800 dark:border-gray-700">
                        <h2 className="text-lg font-semibold mb-4 flex items-center gap-2">
                            <Calendar className="w-5 h-5" />
                            📅 Upcoming Events
                        </h2>
                        <div className="space-y-3">
                            {upcoming_events.length > 0 ? (
                                upcoming_events.map((event) => (
                                    <div key={event.id} className="border rounded-lg p-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <div className="flex items-start justify-between">
                                            <div className="flex-1">
                                                <h3 className="font-medium text-gray-900 dark:text-gray-100">
                                                    {event.title}
                                                </h3>
                                                <p className="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                    📍 {event.location}
                                                </p>
                                                <p className="text-sm text-blue-600 dark:text-blue-400 mt-1">
                                                    🕒 {formatDate(event.start_datetime)}
                                                </p>
                                            </div>
                                            <span className={`px-2 py-1 text-xs rounded-full ${
                                                event.status === 'confirmed' 
                                                    ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                                    : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
                                            }`}>
                                                {event.status}
                                            </span>
                                        </div>
                                    </div>
                                ))
                            ) : (
                                <div className="text-center py-8 text-gray-500 dark:text-gray-400">
                                    <Calendar className="w-12 h-12 mx-auto mb-2 opacity-50" />
                                    <p>No upcoming events</p>
                                </div>
                            )}
                        </div>
                    </div>
                </div>

                {/* Recent Financial Activity */}
                {recent_financials.length > 0 && (
                    <div className="bg-white rounded-lg p-6 shadow-sm border dark:bg-gray-800 dark:border-gray-700">
                        <h2 className="text-lg font-semibold mb-4 flex items-center gap-2">
                            <Clock className="w-5 h-5" />
                            💳 Recent Financial Activity
                        </h2>
                        <div className="space-y-3">
                            {recent_financials.map((record) => (
                                <div key={record.id} className="flex items-center justify-between p-3 border rounded-lg">
                                    <div className="flex items-center gap-3">
                                        <div className={`p-2 rounded-lg ${
                                            ['income', 'contribution', 'donation'].includes(record.type)
                                                ? 'bg-green-100 dark:bg-green-900'
                                                : 'bg-red-100 dark:bg-red-900'
                                        }`}>
                                            {['income', 'contribution', 'donation'].includes(record.type) ? (
                                                <TrendingUp className="w-4 h-4 text-green-600 dark:text-green-400" />
                                            ) : (
                                                <TrendingDown className="w-4 h-4 text-red-600 dark:text-red-400" />
                                            )}
                                        </div>
                                        <div>
                                            <p className="font-medium text-gray-900 dark:text-gray-100">
                                                {record.description}
                                            </p>
                                            <p className="text-sm text-gray-600 dark:text-gray-400">
                                                {record.category} • {formatDate(record.transaction_date)}
                                            </p>
                                        </div>
                                    </div>
                                    <div className="text-right">
                                        <p className={`font-semibold ${
                                            ['income', 'contribution', 'donation'].includes(record.type)
                                                ? 'text-green-600 dark:text-green-400'
                                                : 'text-red-600 dark:text-red-400'
                                        }`}>
                                            {['income', 'contribution', 'donation'].includes(record.type) ? '+' : '-'}
                                            {formatCurrency(record.amount)}
                                        </p>
                                        <p className="text-xs text-gray-500 dark:text-gray-500">
                                            {record.household?.head_name || 'System'}
                                        </p>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                )}
            </div>
        </AppLayout>
    );
}