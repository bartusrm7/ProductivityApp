import Sidebar from "../navigation/Sidebar";
import NavbarMenu from "../navigation/NavbarMenu";
import { useEffect, useState } from "react";
import { Button, Form } from "react-bootstrap";
import { Link } from "react-router-dom";
import type { UserTaskData } from "../../types/tasks";
import DisplayAllLogs from "./DisplayAllLogs";

export default function Dashboard() {
	const [showMenu, setShowMenu] = useState<boolean>(false);
	const [tasksStatus, setTasksStatus] = useState<{ key: string; name: string }[]>([
		{ key: "todo", name: "To Do" },
		{ key: "in_progress", name: "In Progress" },
		{ key: "done", name: "Done" },
	]);
	const [selectedStatus, setSelectedStatus] = useState<string>("");
	const [refresh, setRefresh] = useState<number>(0);
	const [tasksName, setTasksName] = useState<UserTaskData[]>([]);
	const [errorTasksName, setErrorTasksName] = useState<string>("");

	async function getTodayTasks() {
		const jwt = localStorage.getItem("jwt");
		const response = await fetch(`http://productivityapp.local/get-today-tasks?status=${selectedStatus}`, {
			method: "GET",
			headers: {
				"Content-Type": "application/json",
				Authorization: `Bearer ${jwt}`,
			},
		});
		const data = await response.json();
		if (data.success) {
			setTasksName(data.data);
		} else {
			setErrorTasksName(`Tasks with ${selectedStatus} array is empty`);
		}
	}

	const handleGetTaskStatus = (e: any) => {
		setSelectedStatus(e.target.value);
	};

	useEffect(() => {
		if (selectedStatus !== "") {
			getTodayTasks();
		}
	}, [selectedStatus]);

	useEffect(() => {
		document.title = "ProductivityApp - Dashboard";
	}, [refresh]);

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
									<div className='mb-0 pb-0'>Board to display the most important data</div>
								</div>
							</div>
						</div>
						<div className='col-12 col-md-6'>
							<div className='dashboard__main-container h-100 rounded-3'>
								<div className='p-3 p-md-4'>
									<div className='d-flex justify-content-between align-items-center'>
										<h2 className='mb-0'>Last actions</h2>
									</div>
									<div>
										<DisplayAllLogs />
									</div>
								</div>
							</div>
						</div>
						<div className='col-12 col-md-6'>
							<div className='dashboard__main-container h-100 rounded-3'>
								<div className='p-3 p-md-4'>
									<div className='d-flex justify-content-between align-items-center'>
										<h2 className='mb-0'>Today tasks</h2>
										<Form>
											<Form.Select onClick={handleGetTaskStatus}>
												<option value=''></option>
												{tasksStatus.map((status, index) => (
													<option key={index} value={status.key}>
														{status.name}
													</option>
												))}
											</Form.Select>
										</Form>
									</div>
									<div>
										{tasksName.map((task, index) => (
											<div className='dashboard__task-row' key={index}>
												{task.name}
											</div>
										))}
									</div>
								</div>
							</div>
						</div>
						<div className='col-12'>
							<div className='dashboard__main-container h-100 rounded-3'>
								<div className='p-3 p-md-4'>
									<div className='d-flex justify-content-between align-items-center'>
										<h2 className='mb-0'>Last training</h2>
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
