import { useState } from "react";
import { Modal, Form, Button } from "react-bootstrap";
import { CiEdit } from "react-icons/ci";
import type { UserGoalsData } from "../../types/goals";

export default function EditGoal({ goalProp, refreshData }: { goalProp: UserGoalsData; refreshData: () => void }) {
	const [goalData, setGoalData] = useState<UserGoalsData>({ id: 0, name: "", description: "", status: "", type: "", progress: 0, created_at: "", deadline: "" });
	const [showModal, setShowModal] = useState<boolean>(false);
	const [errorsArray, setErrorsArray] = useState<string[]>([]);

	const handleOpenModal = () => {
		setShowModal(true);
		setGoalData(goalProp);
	};

	const handleCloseModal = () => {
		setShowModal(false);
	};

	async function handleEditGoal(e: any) {
		e.preventDefault();
		try {
			const jwt = localStorage.getItem("jwt");
			const response = await fetch("http://productivityapp.local/edit-goal", {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					Authorization: `Bearer ${jwt}`,
				},
				body: JSON.stringify(goalData),
			});
			const data = await response.json();
			if (data.errors) {
				setErrorsArray(data.errors);
			} else {
				setShowModal(false);
				refreshData();
			}
		} catch (error) {
			setErrorsArray(["Server error. Try again."]);
		}
	}

	return (
		<>
			<button className='action-btn edit-action-btn me-2' onClick={handleOpenModal}>
				<CiEdit size={24} />
			</button>

			{showModal && (
				<Modal show={showModal} onHide={handleCloseModal}>
					<Modal.Header closeButton>
						<Modal.Title>Edit current Goal</Modal.Title>
					</Modal.Header>
					<Modal.Body>
						<Form onSubmit={handleEditGoal}>
							<Form.Group className='mb-3'>
								<Form.Floating>
									<Form.Control value={goalData.name} onChange={e => setGoalData(prevState => ({ ...prevState, name: e.target.value }))} type='text' placeholder='' />
									<Form.Label>Goal name</Form.Label>
								</Form.Floating>
							</Form.Group>
							<Form.Group className='mb-3'>
								<Form.Floating>
									<Form.Control value={goalData.description} onChange={e => setGoalData(prevState => ({ ...prevState, description: e.target.value }))} type='text' placeholder='' />
									<Form.Label>Goal description</Form.Label>
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
