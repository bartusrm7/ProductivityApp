import { useState } from "react";
import { Button, Modal, Form } from "react-bootstrap";
import type { UserCreateGoalData } from "../../types/goals";

export default function CreateGoal({ show, handleOpenModal, handleCloseModal, refreshData }: { show: boolean; handleOpenModal: () => void; handleCloseModal: () => void; refreshData: () => void }) {
	const [goalsData, setGoalsData] = useState<UserCreateGoalData>({ name: "", type: "", created_at: new Date().toISOString() });
	const [errorsArray, setErrorsArray] = useState<string[]>([]);

	async function handleCreateNewGoal(e: any) {
		e.preventDefault();
		try {
			const jwt = localStorage.getItem("jwt");
			const response = await fetch("http://productivityapp.local/create-goal", {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					Authorization: `Bearer ${jwt}`,
				},
				body: JSON.stringify(goalsData),
			});
			const data = await response.json();
			if (data.errors) {
				setErrorsArray(data.errors);
			} else {
				handleCloseModal();
				setGoalsData({ name: "", type: "", created_at: new Date().toISOString() });
				refreshData();
			}
		} catch (error) {
			setErrorsArray(["Server error. Try again."]);
		}
	}

	return (
		<div className='create-goal'>
			<Button className='create-goal__create-btn custom-btn' onClick={handleOpenModal}>
				Create new goal
			</Button>

			<Modal show={show} onHide={handleCloseModal}>
				<Modal.Header closeButton>
					<Modal.Title>Create new goal</Modal.Title>
				</Modal.Header>
				<Modal.Body>
					<Form onSubmit={handleCreateNewGoal}>
						<Form.Group className=' mb-3'>
							<Form.Floating>
								<Form.Control value={goalsData.name} onChange={e => setGoalsData(prevState => ({ ...prevState, name: e.target.value }))} type='text' placeholder='Create new goal...' />
								<Form.Label>Goal name</Form.Label>
							</Form.Floating>
						</Form.Group>
						<Form.Group className='mb-3'>
							<Form.Select value={goalsData.type} onChange={e => setGoalsData(prevState => ({ ...prevState, type: e.target.value }))}>
								<option value=''>Select goal type</option>
								<option value='simple'>Simple</option>
								<option value='progress'>Progress</option>
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
