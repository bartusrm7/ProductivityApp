import { Nav, Navbar } from "react-bootstrap";

export default function Sidebar() {
	return (
		<>
			<Navbar className='sidebar d-none p-0'>
				<Navbar.Collapse className='d-flex flex-column justify-content-between align-items-start vh-100 p-3'>
					<Nav className='d-block'>
						<Navbar.Brand className='p-2 fw-bold' href='/dashboard'>
							ProductivityApp
						</Navbar.Brand>
						<hr />
						<Nav.Link className='sidebar__nav-link' href='/dashboard'>
							Dashboard
						</Nav.Link>
						<Nav.Link className='sidebar__nav-link' href='/tasks'>
							Tasks
						</Nav.Link>
						<Nav.Link className='sidebar__nav-link' href='/habits'>
							Habits
						</Nav.Link>
						<Nav.Link className='sidebar__nav-link' href='/notes'>
							Notes
						</Nav.Link>
						<Nav.Link className='sidebar__nav-link' href='/goals'>
							Goals
						</Nav.Link>
						<Nav.Link className='sidebar__nav-link' href='/settings'>
							Settings
						</Nav.Link>
					</Nav>
					<Nav className='d-block w-100'>
						<hr />
						<Nav.Link className='sidebar__nav-link' href='/logout'>
							Logout
						</Nav.Link>
					</Nav>
				</Navbar.Collapse>
			</Navbar>
		</>
	);
}
