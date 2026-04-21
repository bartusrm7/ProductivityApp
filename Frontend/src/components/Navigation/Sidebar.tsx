import { Nav, Navbar } from "react-bootstrap";
import { sidebarData } from "./sidebarData";
import { FiLogOut } from "react-icons/fi";
import { useNavigate } from "react-router-dom";

export default function Sidebar() {
	const navigate = useNavigate();

	const handleUserLogout = () => {
		localStorage.removeItem("jwt");
		navigate("/sign-in");
	};

	return (
		<>
			<Navbar className='sidebar p-0'>
				<Navbar.Collapse className='d-flex flex-column justify-content-between align-items-start vh-100 p-3'>
					<Nav className='d-block'>
						<Navbar.Brand className='p-2 fw-bold' href='/dashboard'>
							ProductivityApp
						</Navbar.Brand>
						<hr />
						{sidebarData.map((data, index) => (
							<Nav.Link key={index} className='sidebar__nav-link d-flex' href={data.href}>
								{data.icon && <data.icon style={{ margin: "0 0.75rem 0 0" }} size={24} />}
								{data.name}
							</Nav.Link>
						))}
					</Nav>
					<Nav className='d-block w-100'>
						<hr />
						<p className='sidebar__nav-link' onClick={handleUserLogout}>
							<FiLogOut style={{ margin: "0 0.75rem 0 0" }} size={24} />
							Logout
						</p>
					</Nav>
				</Navbar.Collapse>
			</Navbar>
		</>
	);
}
