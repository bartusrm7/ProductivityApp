import { useEffect, useState } from "react";
import type { UserNotesDataJoined } from "../../types/notes";
import { IoIosArrowDown, IoIosArrowUp } from "react-icons/io";

export default function DisplaySavedToHistoryNotes() {
	const [notesData, setNotesData] = useState<UserNotesDataJoined[]>([]);
	const [directionSort, setDirectionSort] = useState<"asc" | "desc">("asc");
	const [sortDataKey, setSortDataKey] = useState<string>();
	const [refresh, setRefresh] = useState<number>(0);

	async function getAllNotes() {
		const jwt = localStorage.getItem("jwt");
		const response = await fetch("http://productivityapp.local/get-saved-notes", {
			method: "GET",
			headers: {
				"Content-Type": "application/json",
				Authorization: `Bearer ${jwt}`,
			},
		});
		const data = await response.json();
		if (data.success) {
			setNotesData(data.data);
			setRefresh(prevState => prevState + 1);
		}
	}

	const handleSortFunction = (e: any) => {
		const key = e.target.value;

		if (key) {
			setDirectionSort(prevState => (prevState === "asc" ? "asc" : "desc"));
		} else {
			setDirectionSort("asc");
		}
		setSortDataKey(key);
	};

	useEffect(() => {
		getAllNotes();
	}, [refresh]);

	useEffect(() => {}, [directionSort, sortDataKey]);

	return (
		<div className='display-note w-100'>
			<div className='d-flex align-items-center'>
				<h4 className='ms-2 mb-0'>History notes</h4>
			</div>
			<div className='header-custom-table-names d-none d-md-flex fw-bold border-bottom'>
				<div className='col-1'>
					#
					<button className='sort-btn' onClick={handleSortFunction} value='id'>
						{directionSort === "asc" && sortDataKey === "id" ? <IoIosArrowUp className='sort-icon' size={24} /> : <IoIosArrowDown className='sort-icon' size={24} />}
					</button>
				</div>
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
					<div className='display-note__name col-11 col-md-3'>{note.name}</div>
					<div className='display-note__tag col-9 col-md-2'>{note.tag}</div>
					<div className='display-note__created_at col-7 col-md-2'>{note.date_saved}</div>
				</div>
			))}
		</div>
	);
}
