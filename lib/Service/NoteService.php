<?php

/**
 *
 * @copyright Copyright (c) 2022, Luděk Huška (xhuska02@vutbr.cz)
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 */

declare(strict_types=1);
// SPDX-FileCopyrightText: Luděk Huška <xhuska02@vutbr.cz>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\TwofactorMobile\Service;

use Exception;

use OCA\TwofactorMobile\Db\Note;
use OCA\TwofactorMobile\Db\NoteMapper;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;

class NoteService {
	private NoteMapper $mapper;

	public function __construct(NoteMapper $mapper) {
		$this->mapper = $mapper;
	}

	/**
	 * @return list<Note>
	 */
	public function findAll(string $userId): array {
		return $this->mapper->findAll($userId);
	}

	/**
	 * @return never
	 */
	private function handleException(Exception $e) {
		if ($e instanceof DoesNotExistException ||
			$e instanceof MultipleObjectsReturnedException) {
			throw new NoteNotFound($e->getMessage());
		} else {
			throw $e;
		}
	}

	public function find(int $id, string $userId): Note {
		try {
			return $this->mapper->find($id, $userId);
		} catch (Exception $e) {
			$this->handleException($e);
		}
	}

	public function create(string $title, string $content, string $userId): Note {
		$note = new Note();
		$note->setTitle($title);
		$note->setContent($content);
		$note->setUserId($userId);
		return $this->mapper->insert($note);
	}

	public function update(int $id, string $title, string $content, string $userId): Note {
		try {
			$note = $this->mapper->find($id, $userId);
			$note->setTitle($title);
			$note->setContent($content);
			return $this->mapper->update($note);
		} catch (Exception $e) {
			$this->handleException($e);
		}
	}

	public function delete(int $id, string $userId): Note {
		try {
			$note = $this->mapper->find($id, $userId);
			$this->mapper->delete($note);
			return $note;
		} catch (Exception $e) {
			$this->handleException($e);
		}
	}
}
