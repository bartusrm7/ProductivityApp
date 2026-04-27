import { useEffect, useState } from "react";
import { Button, Modal } from "react-bootstrap";
import { CiEdit } from "react-icons/ci";
import { Form } from "react-bootstrap";
import type { UserTaskData } from "../../types/tasks";

export default function TaskEdit({ taskProp }: { taskProp: UserTaskData }) {
	const [taskData, setTaskData] = useState<UserTaskData>({
		id: null,
		name: "",
		createdAt: new Date().toISOString(),
		priority: "",
		status: "",
	});
	const [showModal, setShowModal] = useState<boolean>(false);
	const [errorsArray, setErrorsArray] = useState<string[]>([]);

	const handleOpenModal = () => {
		setShowModal(true);
		setTaskData(taskProp);
	};

	const handleCloseModal = () => {
		setShowModal(false);
		setTaskData(taskProp);
	};

	async function handleEditTask(e: any) {
		e.preventDefault();
		try {
			const jwt = localStorage.getItem("jwt");
			const response = await fetch("http://productivityapp.local/edit-task", {
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
				setShowModal(false);
			}
		} catch (error) {
			setErrorsArray(["Server error. Try again."]);
		}
	}

	useEffect(() => {}, [taskData, showModal]);

	return (
		<>
			<Button className='bg-primary me-2' onClick={handleOpenModal}>
				<CiEdit size={24} />
			</Button>

			{showModal && (
				<Modal show={showModal} onHide={handleCloseModal}>
					<Modal.Header closeButton>
						<Modal.Title> Create new task</Modal.Title>
					</Modal.Header>
					<Modal.Body>
						<Form onSubmit={handleEditTask}>
							<Form.Group className='mb-3'>
								<Form.Floating>
									<Form.Control value={taskData.name} onChange={e => setTaskData(prevState => ({ ...prevState, name: e.target.value }))} type='text' placeholder='Edit task...' />
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

							{errorsArray && (
								<div>
									{errorsArray.map((error, index) => (
										<div key={index} className='alert alert-danger'>
											{error}
										</div>
									))}
								</div>
							)}

							<Button className='custom-btn w-100' type='submit'>
								Edit
							</Button>
						</Form>
					</Modal.Body>
				</Modal>
			)}
		</>
	);
}
