import { useState } from "react";
import { Modal, Form, Button } from "react-bootstrap";
import { CiEdit } from "react-icons/ci";
import type { UserHabitData } from "../../types/habits";

export default function EditHabit({ habitProp }: { habitProp: UserHabitData }) {
	const [habitData, setHabitData] = useState<UserHabitData>({ id: 0, name: "", description: "", created_at: "" });
	const [showModal, setShowModal] = useState<boolean>(false);
	const [errorsArray, setErrorsArray] = useState<string[]>([]);

	const handleOpenModal = () => {
		setShowModal(true);
		setHabitData(habitProp);
	};

	const handleCloseModal = () => {
		setShowModal(false);
	};

	async function handleEditHabit(e: any) {
		e.preventDefault();
		try {
			const jwt = localStorage.getItem("jwt");
			const response = await fetch("http://productivityapp.local/edit-habit", {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					Authorization: `Bearer ${jwt}`,
				},
				body: JSON.stringify(habitData),
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

	return (
		<>
			<button className='action-btn me-2' onClick={handleOpenModal}>
				<CiEdit size={24} />
			</button>

			{showModal && (
				<Modal show={showModal} onHide={handleCloseModal}>
					<Modal.Header closeButton>
						<Modal.Title>Edit current habit</Modal.Title>
					</Modal.Header>
					<Modal.Body>
						<Form onSubmit={handleEditHabit}>
							<Form.Group className='mb-3'>
								<Form.Floating>
									<Form.Control value={habitData.name} onChange={e => setHabitData(prevState => ({ ...prevState, name: e.target.value }))} type='text' placeholder='' />
									<Form.Label>Habit name</Form.Label>
								</Form.Floating>
							</Form.Group>
							<Form.Group className='mb-3'>
								<Form.Floating>
									<Form.Control value={habitData.description} onChange={e => setHabitData(prevState => ({ ...prevState, description: e.target.value }))} type='text' placeholder='' />
									<Form.Label>Habit description</Form.Label>
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
								Edit
							</Button>
						</Form>
					</Modal.Body>
				</Modal>
			)}
		</>
	);
}
