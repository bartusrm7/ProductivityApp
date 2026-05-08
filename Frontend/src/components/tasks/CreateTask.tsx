import { useState } from "react";
import type { UserTaskData } from "../../types/tasks";
import { Button, Form, Modal } from "react-bootstrap";

export default function CreateTask({ show, handleOpenModal, handleCloseModal, refreshData }: { show: boolean; handleOpenModal: () => void; handleCloseModal: () => void; refreshData: () => void }) {
	const [taskData, setTaskData] = useState<UserTaskData>({
		id: 0,
		name: "",
		created_at: new Date().toISOString(),
		priority: "",
		status: "",
	});
	const [errorsArray, setErrorsArray] = useState<string[]>([]);

	async function handleCreateNewTask(e: any) {
		e.preventDefault();
		try {
			const jwt = localStorage.getItem("jwt");
			const response = await fetch("http://productivityapp.local/create-task", {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					Authorization: `Bearer ${jwt}`,
				},
				body: JSON.stringify(taskData),
			});
			const data = await response.json();
			if (data.errors) {
				setErrorsArray(data.errors);
			} else {
				setErrorsArray([]);
				handleCloseModal();
				setTaskData({
					id: 0,
					name: "",
					created_at: "",
					priority: "",
					status: "",
				});
				refreshData();
			}
		} catch (error) {
			setErrorsArray(["Server error. Try again."]);
		}
	}

	return (
		<div className='create-tasks'>
			<Button className='create-tasks__create-btn custom-btn' onClick={handleOpenModal}>
				Create new task
			</Button>

			<Modal show={show} onHide={handleCloseModal}>
				<Modal.Header closeButton>
					<Modal.Title> Create new task</Modal.Title>
				</Modal.Header>
				<Modal.Body>
					<Form onSubmit={handleCreateNewTask}>
						<Form.Group className=' mb-3'>
							<Form.Floating>
								<Form.Control value={taskData.name} onChange={e => setTaskData(prevState => ({ ...prevState, name: e.target.value }))} type='text' placeholder='Create new task...' />
								<Form.Label>Task</Form.Label>
							</Form.Floating>
						</Form.Group>
						<Form.Group className='mb-3'>
							<Form.Select value={taskData.priority} onChange={e => setTaskData(prevState => ({ ...prevState, priority: e.target.value }))}>
								<option value=''>Select priority</option>
								<option value='low'>Low</option>
								<option value='medium'>Medium</option>
								<option value='high'>High</option>
							</Form.Select>
						</Form.Group>

						{errorsArray.length > 0 && (
							<div>
								{errorsArray.map((error, index) => (
									<div key={index} className='alert alert-danger'>
										{error}
									</div>
								))}
							</div>
						)}

						<Button className='custom-btn w-100' type='submit'>
							Create
						</Button>
					</Form>
				</Modal.Body>
			</Modal>
		</div>
	);
}
