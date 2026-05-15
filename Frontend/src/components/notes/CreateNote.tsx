import { useState } from "react";
import type { UserNotesData } from "../../types/notes";
import { Button, Modal, Form } from "react-bootstrap";

export default function CreateNote({ show, handleOpenModal, handleCloseModal, refreshData }: { show: boolean; handleOpenModal: () => void; handleCloseModal: () => void; refreshData: () => void }) {
	const [notesData, setNotesData] = useState<UserNotesData>({ id: 0, name: "", tag: "", created_at: new Date().toISOString(), important: false });
	const [errorsArray, setErrorsArray] = useState<string[]>([]);

	async function handleCreateNewNote(e: any) {
		e.preventDefault();
		try {
			const jwt = localStorage.getItem("jwt");
			const response = await fetch("http://productivityapp.local/create-note", {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					Authorization: `Bearer ${jwt}`,
				},
				body: JSON.stringify(notesData),
			});
			const data = await response.json();
			if (data.errors) {
				setErrorsArray(data.errors);
			} else {
				refreshData();
			}
		} catch (error) {
			setErrorsArray(["Server error. Try again."]);
		}
	}

	return (
		<div className='create-note'>
			<Button className='create-note__create-btn custom-btn' onClick={handleOpenModal}>
				Create new note
			</Button>

			<Modal show={show} onHide={handleCloseModal}>
				<Modal.Header closeButton>
					<Modal.Title>Create new note</Modal.Title>
				</Modal.Header>
				<Modal.Body>
					<Form onSubmit={handleCreateNewNote}>
						<Form.Group className=' mb-3'>
							<Form.Floating>
								<Form.Control value={notesData.name} onChange={e => setNotesData(prevState => ({ ...prevState, name: e.target.value }))} type='text' placeholder='Create new task...' />
								<Form.Label>Note</Form.Label>
							</Form.Floating>
						</Form.Group>
						<Form.Group className=' mb-3'>
							<Form.Floating>
								<Form.Control value={notesData.tag} onChange={e => setNotesData(prevState => ({ ...prevState, tag: e.target.value }))} type='text' placeholder='Create new task...' />
								<Form.Label>Tag</Form.Label>
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
							Create
						</Button>
					</Form>
				</Modal.Body>
			</Modal>
		</div>
	);
}
