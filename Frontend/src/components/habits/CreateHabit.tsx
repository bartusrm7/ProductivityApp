import { useState } from "react";
import { Button, Modal, Form } from "react-bootstrap";
import type { UserHabitData } from "../../types/habits";

export default function CreateHabit({ show, handleOpenModal, handleCloseModal }: { show: boolean; handleOpenModal: any; handleCloseModal: any }) {
	const [habitsData, setHabitsData] = useState<UserHabitData>({ id: 0, name: "", description: "", created_at: new Date().toISOString() });
	const [errorsArray, setErrorsArray] = useState<string[]>([]);

	async function handleCreateNewHabit(e: any) {
		e.preventDefault();
		try {
			const jwt = localStorage.getItem("jwt");
			const response = await fetch("http://productivityapp.local/create-habit", {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					Authorization: `Bearer ${jwt}`,
				},
				body: JSON.stringify(habitsData),
			});
			const data = await response.json();
			if (data.errors) {
				setErrorsArray(data.errors);
			} else {
				handleCloseModal();
				setHabitsData({ id: 0, name: "", description: "", created_at: "" });
			}
		} catch (error) {
			setErrorsArray(["Server error. Try again."]);
		}
	}

	return (
		<div className='create-habit'>
			<Button className='create-habit__create-btn custom-btn' onClick={handleOpenModal}>
				Create new habit
			</Button>

			<Modal show={show} onHide={handleCloseModal}>
				<Modal.Header closeButton>
					<Modal.Title> Create new task</Modal.Title>
				</Modal.Header>
				<Modal.Body>
					<Form onSubmit={handleCreateNewHabit}>
						<Form.Group className=' mb-3'>
							<Form.Floating>
								<Form.Control value={habitsData.name} onChange={e => setHabitsData(prevState => ({ ...prevState, name: e.target.value }))} type='text' placeholder='Create new task...' />
								<Form.Label>Task</Form.Label>
							</Form.Floating>
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
							Create
						</Button>
					</Form>
				</Modal.Body>
			</Modal>
		</div>
	);
}
