import Sidebar from "../navigation/Sidebar";
import NavbarMenu from "../navigation/NavbarMenu";
import { useState } from "react";
import { Button } from "react-bootstrap";
import { Link } from "react-router-dom";

export default function Dashboard() {
	const [showMenu, setShowMenu] = useState<boolean>(false);
	const [tasksStatus, setTasksStatus] = useState<string[]>(["To Do", "In Progress", "Done"]);

	return (
		<>
			<Sidebar isMenuOpen={showMenu} />
			<NavbarMenu pageName={"Dashboard"} onToggleMenu={() => setShowMenu(prevState => !prevState)} />
			<div className='dashboard'>
				<div className='container-fluid'>
					<div className='row g-3'>
						<div className='col-12'>
							<div className='dashboard__main-container h-100 rounded-3'>
								<div className='p-3 p-md-4 mb-0'>
									<div className='d-flex justify-content-between align-items-center'>
										<h2 className='mb-0'>Dashboard</h2>
										<div>
											<Link to='/tasks'>
												<Button className='dashboard__link-btn custom-btn new-task-link-btn mb-0 me-2'>Add new task</Button>
											</Link>
											<Link to='/habits'>
												<Button className='dashboard__link-btn custom-btn new-habit-link-btn mb-0'>Add new habit</Button>
											</Link>
										</div>
									</div>
									<div className='mb-0 pb-0'>Board to display most important data</div>
								</div>
							</div>
						</div>
						<div className='col-12 col-md-6'>
							<div className='dashboard__main-container h-100 rounded-3'>
								<div className='p-3 p-md-4'>
									<div className='d-flex justify-content-between align-items-center'>
										<h2 className='mb-0'>Last actions</h2>
									</div>
								</div>
							</div>
						</div>
						<div className='col-12 col-md-6'>
							<div className='dashboard__main-container h-100 rounded-3'>
								<div className='p-3 p-md-4'>
									<div className='d-flex justify-content-between align-items-center'>
										<h2 className='mb-0'>Today tasks</h2>
										<select>
											<option value=''></option>
											{tasksStatus.map((status, index) => (
												<option key={index} value={status}>
													{status}
												</option>
											))}
										</select>
									</div>
									<div></div>
								</div>
							</div>
						</div>
						<div className='col-12'>
							<div className='dashboard__main-container h-100 rounded-3'>
								<div className='p-3 p-md-4'>
									<div className='d-flex justify-content-between align-items-center'>
										<h2 className='mb-0'>Last training</h2>
										<select>
											<option value=''></option>
											{tasksStatus.map((status, index) => (
												<option key={index} value={status}>
													{status}
												</option>
											))}
										</select>
									</div>
									<div></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</>
	);
}
