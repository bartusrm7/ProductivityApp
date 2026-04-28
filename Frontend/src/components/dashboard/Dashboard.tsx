import Sidebar from "../navigation/Sidebar";
import NavbarMenu from "../navigation/NavbarMenu";
import { useState } from "react";

export default function Dashboard() {
	const [showMenu, setShowMenu] = useState<boolean>(false);

	return (
		<>
			<Sidebar isMenuOpen={showMenu} />
			<NavbarMenu pageName={"Dashboard"} onToggleMenu={() => setShowMenu(prevState => !prevState)} />
		</>
	);
}
