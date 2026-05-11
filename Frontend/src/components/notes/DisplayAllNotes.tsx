import { useEffect, useState } from "react";
import type { UserNotesData } from "../../types/notes";
import { CiMenuKebab } from "react-icons/ci";
import SetNoteImportant from "./SetNoteImportant";
import DeleteNote from "./DeleteNote";

export default function DisplayAllNotes() {
	const [notesData, setNotesData] = useState<UserNotesData[]>([]);
	const [isOpenMenuActionButtons, setIsOpenMenuActionButtons] = useState<number | null>(null);

	async function getAllNotes() {
		const jwt = localStorage.getItem("jwt");
		const response = await fetch("http://productivityapp.local/get-notes", {
			method: "GET",
			headers: {
				"Content-Type": "application/json",
				Authorization: `Bearer ${jwt}`,
			},
		});
		const data = await response.json();
		if (data.success) {
			setNotesData(data.data);
		}
	}

	async function handleUpdateNoteImportant(noteId: number, importantNote: boolean) {
		try {
			const jwt = localStorage.getItem("jwt");
			await fetch("http://productivityapp.local/set-important-note", {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					Authorization: `Bearer ${jwt}`,
				},
				body: JSON.stringify({ id: noteId, important: importantNote }),
			});
			setNotesData(prevState => prevState.map(note => (note.id === noteId ? { ...note, important: !importantNote } : note)));
		} catch (error) {
			console.error("Server error. Try again.", error);
		}
	}

	const handleOpenMenuWithActionButtons = (habitId: number) => {
		setIsOpenMenuActionButtons(prevState => (prevState === habitId ? null : habitId));
	};

	useEffect(() => {
		getAllNotes();
	}, []);

	return (
		<div className='w-100'>
			<div className='d-flex align-items-center'>
				<h4 className='ms-2 mb-0'>Done</h4>
			</div>
			<div className='d-none d-md-flex fw-bold border-bottom py-2'>
				<div className='col-1'>#</div>
				<div className='col-1'></div>
				<div className='col-3'>Note</div>
				<div className='col-2'>Tag</div>
				<div className='col-2'>Date</div>
				<div className='col-3 text-center'>Actions</div>
			</div>
			{notesData.map((note, index) => (
				<div className='d-flex flex-wrap align-items-center border-bottom py-2' key={index}>
					<div className='col-1 d-none d-md-block fw-bold'>{index + 1}.</div>
					<div className='col-1 d-md-block'>
						<SetNoteImportant noteId={note.id} importantNote={note.important} handleImportantNote={handleUpdateNoteImportant} />
					</div>
					<div className='display-note__name-row col-10 col-md-3'>{note.name}</div>
					<div className='col-1 d-md-none text-end'>
						<CiMenuKebab size={24} />
					</div>
					<div className='col-9 col-md-2'>{note.tag}</div>
					<div className='col-2 d-none d-md-flex'>{note.created_at}</div>
					<div className='d-md-none justify-content-center col-3'>
						{isOpenMenuActionButtons === note.id && (
							<div>
								<DeleteNote noteId={note.id} />
							</div>
						)}
					</div>
					<div className='d-none d-md-flex justify-content-center col-3'>
						<DeleteNote noteId={note.id} />
					</div>
				</div>
			))}
		</div>
	);
}
