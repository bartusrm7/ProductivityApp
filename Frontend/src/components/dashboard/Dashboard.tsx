import Sidebar from "../navigation/Sidebar";
import NavbarMenu from "../navigation/NavbarMenu";

export default function Dashboard() {
	return (
		<>
			<Sidebar />
			<NavbarMenu pageName={"Dashboard"} />
		</>
	);
}
