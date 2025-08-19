import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';
import { 
    Search, 
    Plus, 
    Filter,
    Users,
    MapPin,
    Phone,
    Mail,
    Eye,
    Edit,
    Trash2
} from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Households',
        href: '/households',
    },
];

interface Resident {
    id: number;
    name: string;
    status: string;
}

interface Household {
    id: number;
    house_number: string;
    rt_number: string;
    rw_number: string;
    head_name: string;
    phone: string | null;
    email: string | null;
    address: string;
    status: string;
    resident_count: number;
    monthly_contribution: number;
    residents: Resident[];
    created_at: string;
}

interface PaginationData {
    data: Household[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

interface Filters {
    rt_number?: string;
    rw_number?: string;
    status?: string;
    search?: string;
}

interface Props {
    households: PaginationData;
    filters: Filters;
    [key: string]: unknown;
}

export default function HouseholdsIndex({ households, filters }: Props) {
    const [searchQuery, setSearchQuery] = useState(filters.search || '');
    const [showFilters, setShowFilters] = useState(false);
    const [localFilters, setLocalFilters] = useState(filters);

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        router.get('/households', { 
            ...localFilters, 
            search: searchQuery,
            page: 1 
        }, { 
            preserveState: true 
        });
    };

    const handleFilterChange = (key: string, value: string) => {
        const newFilters = { ...localFilters, [key]: value };
        setLocalFilters(newFilters);
        router.get('/households', { 
            ...newFilters, 
            search: searchQuery,
            page: 1 
        }, { 
            preserveState: true 
        });
    };

    const clearFilters = () => {
        setSearchQuery('');
        setLocalFilters({});
        router.get('/households', {}, { preserveState: true });
    };

    const getStatusBadge = (status: string) => {
        const badges = {
            active: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            inactive: 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
            moved: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
        };
        return badges[status as keyof typeof badges] || badges.active;
    };

    const formatCurrency = (amount: number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
        }).format(amount);
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Households" />
            
            <div className="flex h-full flex-1 flex-col gap-6 p-6">
                {/* Header */}
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            🏠 Household Management
                        </h1>
                        <p className="text-gray-600 dark:text-gray-400">
                            Manage and track household information across your RT/RW
                        </p>
                    </div>
                    <Link
                        href="/households/create"
                        className="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors"
                    >
                        <Plus className="w-4 h-4" />
                        Add Household
                    </Link>
                </div>

                {/* Search and Filters */}
                <div className="bg-white rounded-lg p-6 shadow-sm border dark:bg-gray-800 dark:border-gray-700">
                    <div className="flex flex-col lg:flex-row gap-4">
                        {/* Search */}
                        <form onSubmit={handleSearch} className="flex-1">
                            <div className="relative">
                                <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
                                <input
                                    type="text"
                                    placeholder="Search by house number or head name..."
                                    value={searchQuery}
                                    onChange={(e) => setSearchQuery(e.target.value)}
                                    className="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                />
                            </div>
                        </form>

                        {/* Filter Toggle */}
                        <button
                            onClick={() => setShowFilters(!showFilters)}
                            className="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-700 dark:text-gray-100"
                        >
                            <Filter className="w-4 h-4" />
                            Filters
                        </button>
                    </div>

                    {/* Filter Options */}
                    {showFilters && (
                        <div className="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                            <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-300">RT Number</label>
                                    <input
                                        type="text"
                                        placeholder="e.g., 01"
                                        value={localFilters.rt_number || ''}
                                        onChange={(e) => handleFilterChange('rt_number', e.target.value)}
                                        className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                    />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-300">RW Number</label>
                                    <input
                                        type="text"
                                        placeholder="e.g., 02"
                                        value={localFilters.rw_number || ''}
                                        onChange={(e) => handleFilterChange('rw_number', e.target.value)}
                                        className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                    />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-300">Status</label>
                                    <select
                                        value={localFilters.status || ''}
                                        onChange={(e) => handleFilterChange('status', e.target.value)}
                                        className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                    >
                                        <option value="">All Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="moved">Moved</option>
                                    </select>
                                </div>
                                <div className="flex items-end">
                                    <button
                                        onClick={clearFilters}
                                        className="w-full px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500"
                                    >
                                        Clear Filters
                                    </button>
                                </div>
                            </div>
                        </div>
                    )}
                </div>

                {/* Results Summary */}
                <div className="flex items-center justify-between">
                    <p className="text-sm text-gray-600 dark:text-gray-400">
                        Showing {households.data.length} of {households.total} households
                    </p>
                </div>

                {/* Households Grid */}
                <div className="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                    {households.data.map((household) => (
                        <div key={household.id} className="bg-white rounded-lg p-6 shadow-sm border hover:shadow-md transition-shadow dark:bg-gray-800 dark:border-gray-700">
                            {/* Header */}
                            <div className="flex items-start justify-between mb-4">
                                <div className="flex-1">
                                    <h3 className="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                        House {household.house_number}
                                    </h3>
                                    <p className="text-sm text-blue-600 dark:text-blue-400 font-medium">
                                        RT {household.rt_number} / RW {household.rw_number}
                                    </p>
                                </div>
                                <span className={`px-2 py-1 text-xs font-medium rounded-full ${getStatusBadge(household.status)}`}>
                                    {household.status}
                                </span>
                            </div>

                            {/* Household Head Info */}
                            <div className="space-y-2 mb-4">
                                <div className="flex items-center gap-2">
                                    <Users className="w-4 h-4 text-gray-400" />
                                    <span className="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {household.head_name}
                                    </span>
                                </div>
                                {household.phone && (
                                    <div className="flex items-center gap-2">
                                        <Phone className="w-4 h-4 text-gray-400" />
                                        <span className="text-sm text-gray-600 dark:text-gray-400">
                                            {household.phone}
                                        </span>
                                    </div>
                                )}
                                {household.email && (
                                    <div className="flex items-center gap-2">
                                        <Mail className="w-4 h-4 text-gray-400" />
                                        <span className="text-sm text-gray-600 dark:text-gray-400">
                                            {household.email}
                                        </span>
                                    </div>
                                )}
                                <div className="flex items-start gap-2">
                                    <MapPin className="w-4 h-4 text-gray-400 mt-0.5" />
                                    <span className="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                                        {household.address}
                                    </span>
                                </div>
                            </div>

                            {/* Stats */}
                            <div className="grid grid-cols-2 gap-4 mb-4 p-3 bg-gray-50 rounded-lg dark:bg-gray-700">
                                <div className="text-center">
                                    <p className="text-sm text-gray-600 dark:text-gray-400">Residents</p>
                                    <p className="font-semibold text-gray-900 dark:text-gray-100">
                                        {household.resident_count}
                                    </p>
                                </div>
                                <div className="text-center">
                                    <p className="text-sm text-gray-600 dark:text-gray-400">Monthly Fee</p>
                                    <p className="font-semibold text-gray-900 dark:text-gray-100">
                                        {formatCurrency(household.monthly_contribution)}
                                    </p>
                                </div>
                            </div>

                            {/* Actions */}
                            <div className="flex items-center gap-2">
                                <Link
                                    href={`/households/${household.id}`}
                                    className="flex-1 flex items-center justify-center gap-2 px-3 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                                >
                                    <Eye className="w-4 h-4" />
                                    View Details
                                </Link>
                                <Link
                                    href={`/households/${household.id}/edit`}
                                    className="flex items-center justify-center p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors dark:text-gray-400 dark:hover:text-blue-400 dark:hover:bg-blue-900/20"
                                >
                                    <Edit className="w-4 h-4" />
                                </Link>
                                <button
                                    onClick={() => {
                                        if (confirm('Are you sure you want to delete this household?')) {
                                            router.delete(`/households/${household.id}`);
                                        }
                                    }}
                                    className="flex items-center justify-center p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors dark:text-gray-400 dark:hover:text-red-400 dark:hover:bg-red-900/20"
                                >
                                    <Trash2 className="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    ))}
                </div>

                {/* Empty State */}
                {households.data.length === 0 && (
                    <div className="bg-white rounded-lg p-12 text-center shadow-sm border dark:bg-gray-800 dark:border-gray-700">
                        <Users className="w-16 h-16 text-gray-400 mx-auto mb-4" />
                        <h3 className="text-lg font-medium text-gray-900 mb-2 dark:text-gray-100">
                            No households found
                        </h3>
                        <p className="text-gray-600 mb-6 dark:text-gray-400">
                            {Object.keys(filters).length > 0 || searchQuery 
                                ? 'Try adjusting your search or filter criteria.'
                                : 'Get started by adding your first household.'}
                        </p>
                        <Link
                            href="/households/create"
                            className="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors"
                        >
                            <Plus className="w-5 h-5" />
                            Add First Household
                        </Link>
                    </div>
                )}

                {/* Pagination */}
                {households.last_page > 1 && (
                    <div className="flex items-center justify-center gap-2">
                        {Array.from({ length: households.last_page }, (_, i) => i + 1).map((page) => (
                            <Link
                                key={page}
                                href={`/households?page=${page}`}
                                className={`px-3 py-2 rounded-lg text-sm font-medium transition-colors ${
                                    page === households.current_page
                                        ? 'bg-blue-600 text-white'
                                        : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700'
                                }`}
                            >
                                {page}
                            </Link>
                        ))}
                    </div>
                )}
            </div>
        </AppLayout>
    );
}