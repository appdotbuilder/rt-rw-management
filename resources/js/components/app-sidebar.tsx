import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/react';
import { BookOpen, Folder, LayoutGrid, Users, FileText, Calculator, Megaphone, Calendar } from 'lucide-react';
import AppLogo from './app-logo';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Households',
        href: '/households',
        icon: Users,
    },
    {
        title: 'Letters',
        href: '/letters',
        icon: FileText,
    },
    {
        title: 'Financials',
        href: '/financials',
        icon: Calculator,
    },
    {
        title: 'Announcements',
        href: '/announcements',
        icon: Megaphone,
    },
    {
        title: 'Events',
        href: '/events',
        icon: Calendar,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'RT/RW System',
        href: '/',
        icon: Folder,
    },
    {
        title: 'Help & Support',
        href: '#',
        icon: BookOpen,
    },
];

export function AppSidebar() {
    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href="/dashboard" prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={mainNavItems} />
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
