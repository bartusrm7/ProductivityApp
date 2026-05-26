import { useEffect, useState } from "react";
import { Button, Form, Modal } from "react-bootstrap";

export default function SetTaskDeadline({ taskId, taskDeadline }: { taskId: number; taskDeadline: string }) {
	const [deadlineDate, setDeadlineDate] = useState<string>("");
	const [showModal, setShowModal] = useState<boolean>(false);
	const [errorsArray, setErrorsArray] = useState<string[]>([]);

	const handleOpenModal = () => {
		setShowModal(true);
		setDeadlineDate(taskDeadline);
	};

	const handleCloseModal = () => {
		setShowModal(false);
		setDeadlineDate(taskDeadline);
	};

	async function handleSetDeadlineTime(e: any) {
		e.preventDefault();
		try {
			const jwt = localStorage.getItem("jwt");
			const response = await fetch("http://productivityapp.local/set-deadline-day", {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					Authorization: `Bearer ${jwt}`,
				},
				body: JSON.stringify({ taskId: taskId, deadline: deadlineDate }),
			});
			const data = await response.json();
			console.log(data);
			if (data.errors) {
				setErrorsArray(data.errors);
			} else {
				setDeadlineDate(data.data);
				handleCloseModal();
			}
		} catch (error) {
			setErrorsArray(["Server error. Try again."]);
		}
	}

	useEffect(() => {
		setDeadlineDate(taskDeadline);
	}, [taskDeadline]);

	return (
		<>
			<button className='tasks-in-progress__deadline-btn' onClick={handleOpenModal}>
				{taskDeadline || "Set a deadline day"}
			</button>

			{showModal && (
				<Modal show={showModal} onHide={handleCloseModal}>
					<Modal.Header closeButton>
						<Modal.Title>Edit current task</Modal.Title>
					</Modal.Header>
					<Modal.Body>
						<Form onSubmit={handleSetDeadlineTime}>
							<Form.Group className='mb-3'>
								<Form.Floating>
									<Form.Control value={deadlineDate || ""} onChange={e => setDeadlineDate(e.target.value)} type='date' placeholder='Edit deadline day...' />
									<Form.Label>Deadline day</Form.Label>
								</Form.Floating>
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
								Set deadline
							</Button>
						</Form>
					</Modal.Body>
				</Modal>
			)}
		</>
	);
}
