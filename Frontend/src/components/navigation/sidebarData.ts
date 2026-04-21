import { LuLayoutDashboard } from "react-icons/lu";
import { FaTasks } from "react-icons/fa";
import { FiRepeat } from "react-icons/fi";
import { GrNotes } from "react-icons/gr";
import { GoGoal } from "react-icons/go";
import { IoSettingsSharp } from "react-icons/io5";

export const sidebarData = [
	{ icon: LuLayoutDashboard, name: "Dashboard", href: "/dashboard" },
	{ icon: FaTasks, name: "Tasks", href: "/tasks" },
	{ icon: FiRepeat, name: "Habits", href: "/habits" },
	{ icon: GrNotes, name: "Notes", href: "/notes" },
	{ icon: GoGoal, name: "Goals", href: "/goals" },
	{ icon: IoSettingsSharp, name: "Settings", href: "/settings" },
];
