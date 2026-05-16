import { useEffect, useState } from "react";
import type { UserNotesData } from "../../types/notes";
import SetNoteImportant from "./SetNoteImportant";
import DeleteNote from "./DeleteNote";
import EditNote from "./EditNote";
import { IoIosArrowDown, IoIosArrowUp } from "react-icons/io";
import SaveNoteToHistory from "./SaveNoteToHistory";

export default function DisplayAllNotes() {
	const [notesData, setNotesData] = useState<UserNotesData[]>([]);
	const [directionSort, setDirectionSort] = useState<"asc" | "desc">("asc");
	const [sortDataKey, setSortDataKey] = useState<string>();
	const [refresh, setRefresh] = useState<number>(0);

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
				body: JSON.stringify({ id: noteId, important: !importantNote }),
			});
			setNotesData(prevState => prevState.map(note => (note.id === noteId ? { ...note, important: !importantNote } : note)));
		} catch (error) {
			console.error("Server error. Try again.", error);
		}
	}

	async function handleSaveNoteIntoHistory(noteId: number, saveToHistory: boolean) {
		try {
			const jwt = localStorage.getItem("jwt");
			const response = await fetch("http://productivityapp.local/save-note-to-history", {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					Authorization: `Bearer ${jwt}`,
				},
				body: JSON.stringify({ id: noteId, saveToHistory: !saveToHistory }),
			});
			const data = await response.json();
			if (data.success) {
				setNotesData(prevState => prevState.map(note => (note.id === noteId ? { ...note, saveToHistory: !saveToHistory } : note)));
			}
		} catch (error) {
			console.error("Server error. Try again.", error);
		}
	}

	async function sortNotesFunction() {
		const jwt = localStorage.getItem("jwt");
		const response = await fetch(`http://productivityapp.local/sort-notes?direction=${directionSort}&sort=${sortDataKey}`, {
			method: "GET",
			headers: {
				"Content-Type": "application/json",
				Authorization: `Bearer ${jwt}`,
			},
		});
		const data = await response.json();
		if (data.errors) {
		} else {
			setNotesData(data.data);
		}
	}

	const handleSortFunction = (e: any) => {
		const key = e.target.value;

		if (sortDataKey === key) {
			setDirectionSort(prevState => (prevState === "asc" ? "desc" : "asc"));
		} else {
			setDirectionSort("asc");
		}
		setSortDataKey(key);
	};

	useEffect(() => {
		if (sortDataKey) {
			sortNotesFunction();
		}
	}, [directionSort, sortDataKey]);

	useEffect(() => {
		getAllNotes();
	}, [refresh]);

	return (
		<div className='display-note w-100'>
			<div className='d-flex align-items-center'>
				<h4 className='ms-2 mb-0'>Notes</h4>
			</div>
			<div className='header-custom-table-names d-none d-md-flex fw-bold border-bottom'>
				<div className='col-1'>
					#
					<button className='sort-btn' onClick={handleSortFunction} value='id'>
						{directionSort === "asc" && sortDataKey === "id" ? <IoIosArrowUp className='sort-icon' size={24} /> : <IoIosArrowDown className='sort-icon' size={24} />}
					</button>
				</div>
				<div className='col-1'></div>
				<div className='col-3'>
					Note
					<button className='sort-btn' onClick={handleSortFunction} value='name'>
						{directionSort === "asc" && sortDataKey === "name" ? <IoIosArrowUp className='sort-icon' size={24} /> : <IoIosArrowDown className='sort-icon' size={24} />}
					</button>
				</div>
				<div className='col-2'>
					Tag
					<button className='sort-btn' onClick={handleSortFunction} value='tag'>
						{directionSort === "asc" && sortDataKey === "tag" ? <IoIosArrowUp className='sort-icon' size={24} /> : <IoIosArrowDown className='sort-icon' size={24} />}
					</button>
				</div>
				<div className='col-2'>
					Date
					<button className='sort-btn' onClick={handleSortFunction} value='created_at'>
						{directionSort === "asc" && sortDataKey === "created_at" ? <IoIosArrowUp className='sort-icon' size={24} /> : <IoIosArrowDown className='sort-icon' size={24} />}
					</button>
				</div>
				<div className='col-3 text-center'>Actions</div>
			</div>
			{notesData.map((note, index) => (
				<div className='display-note__main-container custom-table-row d-flex flex-wrap align-items-center border-bottom' key={index}>
					<div className='display-note__id col-1 d-none d-md-block fw-bold'>{index + 1}.</div>
					<div className='display-note__important col-1 text-end text-md-center'>
						<SetNoteImportant noteId={note.id} importantNote={note.important} handleImportantNote={handleUpdateNoteImportant} />
					</div>
					<div className='display-note__name col-11 col-md-3'>{note.name}</div>
					<div className='display-note__tag col-9 col-md-2'>{note.tag}</div>
					<div className='display-note__created_at col-7 col-md-2'>{note.created_at}</div>
					<div className='display-note__btns-container d-flex justify-content-end justify-content-md-center col-5 col-md-3'>
						<SaveNoteToHistory noteId={note.id} saveToHistory={note.savedToHistory} handleSaveToHistory={handleSaveNoteIntoHistory} />
						<EditNote noteProp={note} refreshData={() => setRefresh(prevState => prevState + 1)} />
						<DeleteNote noteId={note.id} refreshData={() => setRefresh(prevState => prevState + 1)} />
					</div>
				</div>
			))}
		</div>
	);
}
