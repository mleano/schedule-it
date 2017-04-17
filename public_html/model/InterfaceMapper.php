interface InterfaceMapper {
  public function fetchAll();
  public function addByName(Instructor $instructor, $firstName, $lastName = " ");
  public function removeById($id);
}
